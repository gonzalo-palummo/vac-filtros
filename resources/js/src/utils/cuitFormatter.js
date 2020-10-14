export default function cuitFormatter(cuit) {
  function replaceCuit(array, index, insert) {
    return array.replace(new RegExp(`^(.{${index}})(.)`), `$1${insert}$2`);
  }

  let formattedCuit = replaceCuit(cuit, 2, ' ');
  formattedCuit = replaceCuit(formattedCuit, 11, ' ');
  return formattedCuit;
}
