export default function suppliesMapper(supplies) {
  const suppliesArray = [];

  supplies.forEach(supplie => {
    suppliesArray.push({
      id: supplie.id,
      name: supplie.name,
      lastPrice: supplie.last_purchase_date
        ? supplie.last_purchase_date.unit_price
        : 0,
      code: supplie.code,
      stock: supplie.stock,
      measureName: supplie.measures.name,
      measureId: supplie.measure_id,
      createdDate: supplie.created_at,
      lastPurchaseDate: supplie.last_purchase_date
        ? supplie.last_purchase_date.created_at
        : 'No hay fecha',
    });
  });

  return suppliesArray;
}
