function salesProductsMapper(products) {
  const productsArray = [];
  products.forEach(product => {
    productsArray.push({
      id: product.id,
      name: product.name,
      quantity: product.pivot.amount,
      unitPrice: product.pivot.unit_price,
      totalPrice: product.pivot.amount * product.pivot.unit_price,
    });
  });
  return productsArray;
}

export default function salesMapper(sales) {
  const salesArray = [];

  sales.forEach(sale => {
    salesArray.push({
      id: sale.id,
      clientName: sale.clients.name,
      clientId: sale.clients.id,
      invoiceNumber: sale.invoice_number,
      invoiceDate: sale.invoice_date,
      paymentDate: sale.payment_date,
      statusText: sale.sales_status.name,
      statusId: sale.sales_status.id,
      statusChangedDate: sale.status_changed_date,
      paymentMethodId: sale.payment_method_id,
      paymentMethodName: sale.payment_methods.name,
      productsAdded: salesProductsMapper(sale.products),
      totalPrice: sale.total_price,
    });
  });

  return salesArray;
}
