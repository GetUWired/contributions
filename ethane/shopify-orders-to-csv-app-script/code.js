/**
 * This Google App Script can be used to retrieve all orders from a Shopify store
 * and save them as a CSV file in Google Drive.
 */

async function saveOrdersToCSV() {
  const shopifyStoreUrl = "THE_STORE_URL_GOES_HERE";
  // the param status=any allows pulling archived orders as well
  const shopifyOrdersEndpoint = "/admin/api/2023-04/orders.json?status=any";
  const accessToken = "ACCESS_TOKEN_GOES_HERE";

  // Set up the headers for the API request
  const headers = {
    "Content-Type": "application/json",
    'X-Shopify-Access-Token': accessToken
  };

  const options = {
    "method": "GET",
    "headers": headers,
    "contentType": "application/json",
    "muteHttpExceptions": true,
  };

  // Set up variables for pagination
  const ordersPerPage = 250; // The number of orders to fetch per page
  const url = "https://" + shopifyStoreUrl + shopifyOrdersEndpoint + "&limit=" + ordersPerPage;

  let ordersData = [];

  while (url) {
    // Make the API request to fetch the orders
    let response = UrlFetchApp.fetch(url, options);
    let data = await JSON.parse(response);

    // Append the orders to the ordersData array
    if (data.orders.length > 0) {
      data.orders.forEach(function(order) {
        ordersData.push(order);
      });
    }

    // Move to the next page or break the loop if there are no more pages
    url = getNextPageUrl(response);
  }

  // Convert ordersData to CSV string
  const csvContent = convertToCSV(ordersData);

  // Create a CSV file and save the data
  const fileName = "orders.csv";
  const fileContentBlob = Utilities.newBlob(csvContent, "application/octet-stream");
  const folder = DriveApp.getRootFolder(); // Change to the desired folder if needed
  const file = folder.createFile(fileContentBlob.setName(fileName));
  const fileUrl = file.getUrl();
  console.log("Orders data saved as CSV: " + fileUrl);
}

// Helper function to extract the URL for the next page from the Link header
function getNextPageUrl(response) {
  const linkHeader = response.getHeaders()["Link"];
  if (linkHeader) {
    const nextLink = linkHeader.match(/<([^>]+)>;\s*rel="next"/);
    if (nextLink) {
      return nextLink[1];
    }
  }
  return null; // No next page URL found
}

// Helper function to convert array of objects to CSV string
function convertToCSV(dataArray) {
  const header = Object.keys(dataArray[0]).join(",") + "\n";
  const rows = dataArray.map(function(obj) {
    return Object.values(obj).map(function(value) {
      return typeof value === "string" ? '"' + value.replace(/"/g, '""') + '"' : value;
    }).join(",");
  }).join("\n");
  return header + rows;
}