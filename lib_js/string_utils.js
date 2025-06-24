if (!String.prototype.replaceAll) {
    String.prototype.replaceAll = function(search, replacement) {
        const target = this;
        if (search instanceof RegExp) {
            // pastikan flag "g" ada agar semua cocok diganti
            if (!search.flags.includes('g')) {
                throw new TypeError('RegExp must have global ("g") flag in replaceAll polyfill');
            }
            return target.replace(search, replacement);
        } else {
            return target.split(search).join(replacement);
        }
    };
}
