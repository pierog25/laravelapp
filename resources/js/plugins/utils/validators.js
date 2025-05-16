import en from 'vee-validate/dist/locale/es.json';
import * as rules from 'vee-validate/dist/rules';
import { configure } from 'vee-validate';
import { extend, localize } from 'vee-validate';

Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});

localize('en', en);