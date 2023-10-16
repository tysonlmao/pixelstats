// Function to switch the account titles, tabs, and content
function switchAccounts() {
    const mainTitle = document.getElementById("mainAccountTitle");
    const altTitle = document.getElementById("altAccountTitle");
    const mainTab = document.getElementById("mainAccount-tab");
    const altTab = document.getElementById("altAccount-tab");
    const mainContent = document.getElementById("mainAccount");
    const altContent = document.getElementById("altAccount");

    // Swap the text content of h2 and h3
    const tempTitle = mainTitle.textContent;
    mainTitle.textContent = altTitle.textContent;
    altTitle.textContent = tempTitle;

    // Toggle active content
    mainContent.classList.toggle("show");
    mainContent.classList.toggle("active");
    altContent.classList.toggle("show");
    altContent.classList.toggle("active");
}
// Add event listener to the "Switch Accounts" button
const switchButton = document.getElementById("switchButton");
switchButton.addEventListener("click", function () {
    // Call the function to switch account titles, tabs, and content
    switchAccounts();
});