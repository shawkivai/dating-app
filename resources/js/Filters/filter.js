import Vue from 'vue';

/**
 * Unslugify a slug
 */
Vue.filter('unslugify', str => {
    return str
        .split('-')
        .join(' ')
        .replace(/\w\S*/g, txt => {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
});

/**
 * Showing Enum field as normal text
 K*
 * @return String
 */
Vue.filter('unEnumify', str => {
    if (str) {
        return str
            .split('_')
            .join(' ')
            .replace(/\w\S*/g, txt => {
                return (
                    txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                );
            });
    }
});


/**
 * Uppercase first word
 *
 * @return String
 */
Vue.filter('uppercase', str => {
    if (str) {
        return str.charAt(0).toUpperCase() + str.substr(1).toLowerCase();
    }
});
