export default function phoneNumberFormatter(phoneNumber) {
  function replacePhoneNumber(array, index, insert) {
    return array.replace(new RegExp(`^(.{${index}})(.)`), `$1${insert}$2`);
  }

  phoneNumber = phoneNumber.toString();
  let formattedPhoneNumber = phoneNumber;
  if (phoneNumber.length === 8) {
    formattedPhoneNumber = replacePhoneNumber(phoneNumber, 4, ' ');
  } else if (phoneNumber.length === 10) {
    formattedPhoneNumber = replacePhoneNumber(phoneNumber, 2, ' ');
    formattedPhoneNumber = replacePhoneNumber(formattedPhoneNumber, 7, ' ');
  } else if (phoneNumber.length === 12) {
    formattedPhoneNumber = replacePhoneNumber(phoneNumber, 2, ' ');
    formattedPhoneNumber = replacePhoneNumber(formattedPhoneNumber, 4, ' ');
    formattedPhoneNumber = replacePhoneNumber(formattedPhoneNumber, 10, ' ');
  }
  return formattedPhoneNumber;
}
