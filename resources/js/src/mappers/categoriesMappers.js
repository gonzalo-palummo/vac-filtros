export default function categoriesMapper(category) {
  const categoriesArray = [];
  if (category.length) {
    category.forEach(category => {
      categoriesArray.push({
        value: category.id,
        text: category.name,
      });
    });
  }

  return categoriesArray;
}
