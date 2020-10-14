export default function paymentMethodsMapper(paymentMethods) {
  const paymentMethodsArray = [];

  paymentMethods.forEach(paymentMethod => {
    paymentMethodsArray.push({
      value: paymentMethod.id,
      text: paymentMethod.name,
    });
  });

  return paymentMethodsArray;
}
