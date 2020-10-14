function purchasesSuppliesMapper(supplies) {
  const suppliesArray = [];
  supplies.forEach(supplie => {
    suppliesArray.push({
      id: supplie.id,
      name: supplie.name,
      quantity: supplie.pivot.amount,
      unitPrice: supplie.pivot.unit_price,
      totalPrice: supplie.pivot.amount * supplie.pivot.unit_price,
    });
  });
  return suppliesArray;
}

export default function purchasesMapper(purchases) {
  const purchasesArray = [];

  purchases.forEach(purchase => {
    purchasesArray.push({
      id: purchase.id,
      providerName: purchase.providers.name,
      providerId: purchase.providers.id,
      invoiceNumber: purchase.invoice_number,
      invoiceDate: purchase.invoice_date,
      paymentDate: purchase.payment_date,
      statusText: purchase.purchases_status.name,
      statusId: purchase.purchases_status.id,
      statusChangedDate: purchase.status_changed_date,
      paymentMethodId: purchase.payment_method_id,
      paymentMethodName: purchase.payment_methods.name,
      suppliesAdded: purchasesSuppliesMapper(purchase.supplies),
      totalPrice: Number(purchase.price),
    });
  });

  return purchasesArray;
}
