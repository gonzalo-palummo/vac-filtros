export default function salesStatusMapper(salesStatus) {
  const salesStatusArray = [];

  salesStatus.forEach(saleStatus => {
    salesStatusArray.push({
      value: saleStatus.id,
      text: saleStatus.name,
    });
  });

  return salesStatusArray;
}
