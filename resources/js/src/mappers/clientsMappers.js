import cuitFormatter from '../utils/cuitFormatter';
import phoneNumberFormatter from '../utils/phoneNumberFormatter';
export default function clientsMapper(clients) {
  const clientsArray = [];

  clients.forEach(client => {
    clientsArray.push({
      id: client.id,
      name: client.name,
      cuit: cuitFormatter(client.cuit),
      address: client.address,
      phone: phoneNumberFormatter(client.telephone),
      email: client.email,
      priceList: client.price_list,
    });
  });

  return clientsArray;
}
