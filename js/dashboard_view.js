import axios from 'axios'; // Import Axios using ES6 module syntax
const mainUsername = mainAccountValue;
const getStats = async () => {
    try {
        const response = await fetch(`http://pixelstats.app/requests?uuid=${mainUsername}`);
        console.log(response.data); // Access the response data
    } catch (error) {
        console.error(error);
    }
};

getStats();
