export default function purchasesStatusMapper(purchasesStatus) {
  const purchasesStatusArray = [];

  purchasesStatus.forEach(purchaseStatus => {
    purchasesStatusArray.push({
      value: purchaseStatus.id,
      text: purchaseStatus.name,
    });
  });

  return purchasesStatusArray;
}
