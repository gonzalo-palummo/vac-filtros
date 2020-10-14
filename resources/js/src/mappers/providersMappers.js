import cuitFormatter from '../utils/cuitFormatter';
import phoneNumberFormatter from '../utils/phoneNumberFormatter';
export default function providersMapper(providers) {
  const providersArray = [];

  providers.forEach(provider => {
    providersArray.push({
      id: provider.id,
      name: provider.name,
      cuit: cuitFormatter(provider.cuit),
      address: provider.address,
      phone: phoneNumberFormatter(provider.telephone),
      email: provider.email,
    });
  });

  return providersArray;
}
