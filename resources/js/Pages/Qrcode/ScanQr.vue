<!-- Vue QR Scanner  -->
<template>
     <QuasarLayout>
            <div class="mx-auto max-w-md p-4 bg-gray-100 rounded-md">
                <div v-if="!decodedText2" class="mb-4">
                <StreamBarcodeReader @decode="onDecode" @loaded="onLoaded" ></StreamBarcodeReader>
                </div>
                
                <div v-if="decodedText !== ''" class="bg-white rounded-md p-4">
                <h2 class="text-xl font-bold mb-2">The decoded value in QR/barcode is</h2>
                <h2 class="text-lg">{{ decodedText }}</h2>
                </div>
        </div>
</QuasarLayout>
</template>



<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { ref } from "vue";
import { StreamBarcodeReader } from "vue-barcode-reader";
import CryptoJS from "crypto-js";
import { usePage } from '@inertiajs/vue3';

// Retrieve props passed by Inertia
// const { props } = usePage();
const props = defineProps({
    'appKey' : Object,
})
console.log(props.appKey);

const decodedText = ref("");
const decodedText2 = ref(false);

const onLoaded = () => {
  console.log("loaded");
};

// const onDecode = (text) => {
//   try {
//     // Decrypt the scanned data
//     const bytes = CryptoJS.AES.decrypt(text);
//     const decryptedData = bytes.toString(CryptoJS.enc.Utf8);

//     decodedText.value = decryptedData;
//     decodedText2.value = true;
//     // Extracting location data from the decoded QR code
//     const locationPattern = /Location:\s*Latitude:\s*([0-9.-]+),\s*Longitude:\s*([0-9.-]+)/;
//     const match = text.match(locationPattern);

//   if (match && match.length === 3) {
//     const scannedLatitude = parseFloat(match[1]);
//     const scannedLongitude = parseFloat(match[2]);

//     // Get user's current location
//     if (navigator.geolocation) {
//       navigator.geolocation.getCurrentPosition(
//         (position) => {
//           const userLatitude = position.coords.latitude;
//           const userLongitude = position.coords.longitude;

//           // Calculate distance between scanned location and user's location
//           const distance = calculateDistance(scannedLatitude, scannedLongitude, userLatitude, userLongitude);

//           // Check if the user is within 50 meters of the scanned location
//           if (distance <= 50) { // 50 meters radius
//             console.log('Scanned location is within 50 meters of the user');
//             // Allow access or perform further actions
//           } else {
//             console.log('User is not within 50 meters of the scanned location');
//             // Deny access or perform other actions
//           }
//         },
//         (error) => {
//           console.error('Error getting user location:', error.message);
//           // Handle error fetching user location
//         }
//       );
//     } else {
//       console.error('Geolocation is not supported by this browser.');
//       // Handle if geolocation is not supported
//     }
//   }
//     // Rest of your logic...
//   } catch (error) {
//     console.error('Error decrypting data:', error);
//     // Handle decryption error
//   }
// };

// Show if the scan is happening within 50m radius

const onDecode = (text) => {
    
  decodedText.value = text;
  decodedText2.value = true;

  // Extracting location data from the decoded QR code
  const locationPattern = /Location:\s*Latitude:\s*([0-9.-]+),\s*Longitude:\s*([0-9.-]+)/;
  const match = text.match(locationPattern);

  if (match && match.length === 3) {
    const scannedLatitude = parseFloat(match[1]);
    const scannedLongitude = parseFloat(match[2]);

    // Get user's current location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const userLatitude = position.coords.latitude;
          const userLongitude = position.coords.longitude;

          // Calculate distance between scanned location and user's location
          const distance = calculateDistance(scannedLatitude, scannedLongitude, userLatitude, userLongitude);

          // Check if the user is within 50 meters of the scanned location
          if (distance <= 50) { // 50 meters radius
            console.log('Scanned location is within 50 meters of the user');
            // Allow access or perform further actions
          } else {
            console.log('User is not within 50 meters of the scanned location');
            // Deny access or perform other actions
          }
        },
        (error) => {
          console.error('Error getting user location:', error.message);
          // Handle error fetching user location
        }
      );
    } else {
      console.error('Geolocation is not supported by this browser.');
      // Handle if geolocation is not supported
    }
  }
};
// Function to calculate distance between two points
const calculateDistance = (lat1, lon1, lat2, lon2) => {
  
  // Haversine formula or any other distance calculation logic
    const earthRadius = 6371000; // Radius of the Earth in meters

  const toRadians = (angle) => {
    return angle * (Math.PI / 180);
  };

  const deltaLat = toRadians(lat2 - lat1);
  const deltaLon = toRadians(lon2 - lon1);
  const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
            Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
            Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

  const distance = earthRadius * c; // Distance in meters

  return distance;
};


</script>