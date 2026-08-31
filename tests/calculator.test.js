/**
 * Прогон всех 84 комбинаций калькулятора: 7 вариантов «сколько человек»
 * (1–6 и 7+) × 2 водоотведения × 3 глубины × 2 УГВ.
 *
 * Запуск: node tests/calculator.test.js
 *
 * Данные станций — снимок каталога servis-septik21.ru (ёмкости 4, 5, 9, 10;
 * ёмкостей 6 и 8 в каталоге нет). Проверяем, что:
 *  1) ёмкость модели не ниже числа проживающих;
 *  2) исполнение соответствует ответам — никакого «Пр» при самотёке
 *     и никакого «Лонг» при стандартной глубине;
 *  3) не подставляется станция намного большей ёмкости (баг «6 человек →
 *     ТОПАС-С 9 Лонг Пр», +42 % к цене);
 *  4) надбавка за опцию не начисляется дважды — только если её нет в модели.
 */
'use strict';

var STATIONS = [
    { number: 4,  title: 'ТОПАС-С 4',          price: 115000 },
    { number: 4,  title: 'ТОПАС-С 4 Пр',       price: 123000 },
    { number: 5,  title: 'ТОПАС-С 5',          price: 131400 },
    { number: 5,  title: 'ТОПАС-С 5 Пр',       price: 143200 },
    { number: 9,  title: 'ТОПАС-С 9 Лонг Пр',  price: 189500 },
    { number: 9,  title: 'ТОПАС-С 9 Лонг',     price: 182000 },
    { number: 9,  title: 'ТОПАС-С 9 Пр',       price: 167600 },
    { number: 10, title: 'ТОПАС-С 10 Лонг Пр', price: 246300 },
    { number: 10, title: 'ТОПАС-С 10 Лонг',    price: 236700 },
    { number: 10, title: 'ТОПАС-С 10 Пр',      price: 221900 },
    { number: 10, title: 'ТОПАС-С 10',         price: 212000 }
];

var SETTINGS = {
    installBase: 35000, delivery: 0,
    surchargeForced: 15000, surchargeUgv: 10000,
    surchargeLong: 6000, surchargeLongUs: 12000,
    soilCoeff: 10, remotenessCoeff: 10
};

global.window = { topasCalc: { stations: STATIONS, settings: SETTINGS } };
global.document = { addEventListener: function () {}, querySelectorAll: function () { return []; } };

var calc = require('../assets/js/calculator.js');

var PEOPLE = ['1', '2', '3', '4', '5', '6', '7'];
var DISPOSAL = ['gravity', 'forced'];
var DEPTH = ['standard', 'long', 'longus'];
var UGV = ['no', 'yes'];

var rows = [];
var errors = [];
var combos = 0;

PEOPLE.forEach(function (people) {
    DISPOSAL.forEach(function (disposal) {
        DEPTH.forEach(function (depth) {
            UGV.forEach(function (ugv) {
                combos++;
                var answers = { people: people, disposal: disposal, depth: depth, ugv: ugv };
                var n = parseInt(people, 10);
                var label = people + ' чел / ' + disposal + ' / ' + depth + ' / УГВ ' + ugv;

                // 7+ — индивидуальный подбор, расчёт не показывается.
                if (n >= calc.BIG_HOUSE_FROM) {
                    rows.push([label, 'индивидуальный подбор (7+)', '—']);
                    return;
                }

                var st = calc.pickStation(n, answers);
                if (!st) { errors.push(label + ': модель не подобрана'); return; }

                if (calc.capacityTooBig(st, n)) {
                    rows.push([label, 'индивидуальный подбор (нет ТОПАС-С ' + n + ')', '—']);
                    return;
                }

                var r = calc.computeEstimate(answers);
                rows.push([label, r.modelName, r.low.toLocaleString('ru-RU') + ' – ' + r.high.toLocaleString('ru-RU') + ' ₽']);

                // 1) ёмкость не ниже потребности
                if (st.number < n) errors.push(label + ': ёмкость ' + st.number + ' меньше ' + n);

                // 2) исполнение соответствует ответам
                var v = calc.parseVariant(r.modelName);
                if (v.forced !== (disposal === 'forced')) {
                    errors.push(label + ': исполнение «Пр» не соответствует — ' + r.modelName);
                }
                var wantDepth = depth === 'longus' ? 2 : (depth === 'long' ? 1 : 0);
                var haveDepth = v.longus ? 2 : (v.long ? 1 : 0);
                if (haveDepth !== wantDepth) {
                    errors.push(label + ': глубина не соответствует — ' + r.modelName);
                }

                // 3) не подставляем станцию намного большей ёмкости
                if (st.number - n >= 2 && n > 4) {
                    errors.push(label + ': подставлена ёмкость ' + st.number + ' вместо ' + n);
                }

                // 4) надбавки: только за опции, которых нет в модели поста
                var base = calc.parseVariant(st.title);
                var expected = SETTINGS.installBase;
                if (disposal === 'forced' && !base.forced) expected += SETTINGS.surchargeForced;
                if (depth === 'longus' && !base.longus) expected += SETTINGS.surchargeLongUs;
                else if (depth === 'long' && !base.long && !base.longus) expected += SETTINGS.surchargeLong;
                if (ugv === 'yes') expected += SETTINGS.surchargeUgv;
                if (r.install !== expected) {
                    errors.push(label + ': монтаж ' + r.install + ', ожидалось ' + expected);
                }
            });
        });
    });
});

var w = [0, 0, 0];
rows.forEach(function (r) { r.forEach(function (c, i) { w[i] = Math.max(w[i], String(c).length); }); });
rows.forEach(function (r) {
    console.log(r.map(function (c, i) { return String(c).padEnd(w[i]); }).join('  |  '));
});

console.log('\nКомбинаций проверено: ' + combos);
if (errors.length) {
    console.log('ОШИБОК: ' + errors.length);
    errors.forEach(function (e) { console.log('  ✗ ' + e); });
    process.exit(1);
}
console.log('Все проверки пройдены.');
