fetch('https://epntest.epin.dev/fra/apv2/GetCategoryList')
  .then((response) => response.json())
  .then((data) => console.log(data));