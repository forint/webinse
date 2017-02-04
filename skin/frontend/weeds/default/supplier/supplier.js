/**
 * Created by forint on 4/19/16.
 */
function validate(password) {
    return /[0-9\w\W]{1,6}/.test(password);
}

var value = 'JHD5FJ53';
validate(value);
