function productsSuppliesMapper(supplies) {
  const suppliesArray = [];
  supplies.forEach(supply => {
    suppliesArray.push({
      id: supply.id,
      name: supply.name,
      stock: supply.stock,
      measure: supply.measures.name,
      amount: supply.pivot.supplie_amount,
    });
  });
  return suppliesArray;
}

export default function productsMapper(products) {
  const productsArray = [];

  products.forEach(product => {
    productsArray.push({
      id: product.id,
      stock: product.stock,
      name: product.name,
      categoryId: product.category_id,
      categoryName: product.categories.name,
      code: product.code,
      measurement: product.measurement,
      squareMeters: product.mts,
      price: product.price,
      priceList1: product.price1,
      priceList2: product.price2,
      priceList3: product.price3,
      createdDate: product.created_at,
      supplies: productsSuppliesMapper(product.supplies),
    });
  });

  return productsArray;
}
