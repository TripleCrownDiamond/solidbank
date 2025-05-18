// utils/clerkLocale.ts
import {
    arSA, beBY, bgBG, caES, zhCN, zhTW, hrHR, csCZ, daDK, nlBE, nlNL,
    enGB, enUS, fiFI, frFR, deDE, elGR, heIL, huHU, isIS, itIT, idID,
    jaJP, koKR, mnMN, nbNO, plPL, ptBR, ptPT, roRO, ruRU, skSK,
    esES, esMX, esUY, svSE, thTH, trTR, ukUA, viVN,
} from '@clerk/localizations';

const clerkLocales: Record<string, any> = {
    ar: arSA,
    be: beBY,
    bg: bgBG,
    ca: caES,
    zh: zhCN, // par défaut simplifié
    zhCN: zhCN,
    zhTW: zhTW,
    hr: hrHR,
    cs: csCZ,
    da: daDK,
    nl: nlNL,
    nlNL: nlNL,
    nlBE: nlBE,
    en: enUS,
    enUS: enUS,
    enGB: enGB,
    fi: fiFI,
    fr: frFR,
    frFR: frFR,
    de: deDE,
    el: elGR,
    he: heIL,
    hu: huHU,
    is: isIS,
    it: itIT,
    id: idID,
    ja: jaJP,
    ko: koKR,
    mn: mnMN,
    nb: nbNO,
    pl: plPL,
    pt: ptPT,
    ptBR: ptBR,
    ro: roRO,
    ru: ruRU,
    sk: skSK,
    es: esES,
    esES: esES,
    esMX: esMX,
    esUY: esUY,
    sv: svSE,
    th: thTH,
    tr: trTR,
    uk: ukUA,
    vi: viVN,
};

export function getClerkLocale(locale: string): any {
    // enlever les tirets et capitaliser
    const normalized = locale.replace("-", "").trim();
    return clerkLocales[normalized] || clerkLocales[locale] || enUS;
}
