document.addEventListener("DOMContentLoaded", () => {
    const lookupButton = document.getElementById("lookup");
    const resultDiv = document.getElementById("result");
    const countryInput = document.getElementById("country"); // input field for country

    lookupButton.addEventListener("click", () => {
        //get the value entered by the user
        const country = countryInput.value.trim();

        if (country === "") {
            resultDiv.innerHTML = "<p>Please enter the name of a country.</p>";
            return;
        }

        // fetch data from world.php 
        fetch(`world.php?country=${encodeURIComponent(country)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text(); 
            })
            .then(data => {
                // print the data
                resultDiv.innerHTML = data;
            })
            .catch(error => {
                resultDiv.innerHTML = `<p>Error: ${error.message}</p>`;
            });
    });
});
