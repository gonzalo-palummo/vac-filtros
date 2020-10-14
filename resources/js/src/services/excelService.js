function formatJson(filterVal, jsonData) {
  return jsonData.map(v =>
    filterVal.map(j => {
      // Add col name which needs to be translated
      // if (j === 'timestamp') {
      //   return parseTime(v[j])
      // } else {
      //   return v[j]
      // }
      return v[j];
    })
  );
}

export default {
  generateFile(fileName, items, headerVal, headerTitle) {
    import('@/vendor/Export2Excel').then(excel => {
      const list = items;
      const data = formatJson(headerVal, list);
      let today = new Date();
      const dd = String(today.getDate()).padStart(2, '0');
      const mm = String(today.getMonth() + 1).padStart(2, '0');
      const yyyy = today.getFullYear();
      today = mm + '/' + dd + '/' + yyyy;
      excel.export_json_to_excel({
        header: headerTitle,
        data,
        filename: fileName + ' ' + today,
        autoWidth: true,
        bookType: 'xlsx',
      });
    });
  },
};
