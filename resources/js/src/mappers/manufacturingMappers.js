function manufacturingSuppliesMapper(supplies) {
  const wastesArray = [];
  supplies.forEach(supply => {
    wastesArray.push({
      supplyId: supply.id,
      amount: supply.pivot.waste,
    });
  });
  return wastesArray;
}

export default function manufacturingMapper(productions) {
  const productionsArray = [];

  productions.forEach(production => {
    productionsArray.push({
      id: production.id,
      productId: production.product_id,
      amount: production.amount,
      name: production.products.name,
      createdAt: production.created_at ? production.created_at : 'No hay fecha',
      wastes: manufacturingSuppliesMapper(production.supplies),
    });
  });

  return productionsArray;
}
