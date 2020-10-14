export default function measuresMapper(measures) {
  const measuresArray = [];

  measures.forEach(measure => {
    measuresArray.push({
      value: measure.id,
      text: measure.name,
    });
  });

  return measuresArray;
}
