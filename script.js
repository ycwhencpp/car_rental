// Get car ID from query parameter
const urlParams = new URLSearchParams(window.location.search);
const carId = urlParams.get("car_id");

if (carId) {
  // Load existing car details
  fetch(`get_car.php?car_id=${carId}`)
    .then((response) => response.json())
    .then((car) => {
      document.getElementById("model").value = car.model;
      document.getElementById("vehicle_number").value = car.vehicle_number;
      document.getElementById("seating_capacity").value = car.seating_capacity;
      document.getElementById("rent_per_day").value = car.rent_per_day;

      // Change form submit button text to "Save Changes"
      const submitButton = document.querySelector('input[type="submit"]');
      submitButton.value = "Save Changes";

      // Add hidden input field for car ID
      const carIdInput = document.createElement("input");
      carIdInput.type = "hidden";
      carIdInput.name = "car_id";
      carIdInput.value = carId;
      document.querySelector("form").appendChild(carIdInput);
    })
    .catch((error) => {
      console.error(error);
      alert("Failed to load car details");
    });
}
