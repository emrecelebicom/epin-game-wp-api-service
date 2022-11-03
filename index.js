async function getResponse() {
	const response = await fetch(
		'https://epntest.epin.dev/fra/apv2/GetCategoryList',
		{
			method: 'GET',
			headers: {
				'Authorization': 'ggamigo2',
        'ApiName': '3deb920a91999f5153e350f4b3a3e947',
        'ApiKey': '746057181cb7a5406e843ada1d459233'
			}
		}
	);
}


document.addEventListener("DOMContentLoaded", async () => {
  let data = [];

  try {
    data = await getResponse();
  } catch (error) {
    
  }

});