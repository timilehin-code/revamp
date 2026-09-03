// light mode begings
let lightMode = localStorage.getItem("lightmode");
const modeToggler = document.querySelector(".toggler");

const enableLightMode = () => {
  document.body.classList.add("lightmode");
  localStorage.setItem("lightmode", "enabled");
};

const disableLightMode = () => {
  document.body.classList.remove("lightmode");
  localStorage.setItem("lightmode", "disabled");
};

if (lightMode === "enabled") {
  enableLightMode();
}

modeToggler.addEventListener("click", () => {
  lightMode = localStorage.getItem("lightmode");
  if (lightMode !== "enabled") {
    enableLightMode();
    console.log(lightMode);
  } else if (lightMode == "enabled") {
    disableLightMode();
    console.log(lightMode);
  }
});

// email js

function sendEmail() {
  // Get form values
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const subject = document.getElementById("subject").value;
  const message = document.getElementById("message").value;
  const statusElement = document.getElementById("status");
  const submitBtn = document.querySelector(".send-btn");

  // Validate form
  if (!name || !email || !subject || !message) {
    statusElement.textContent = "Please fill in all fields";
    statusElement.className = "text-danger text-center mt-4";
    statusElement.style.display = "block";
    setTimeout(() => {
      statusElement.style.display = "none";
    }, 5000);
    return;
  }

  // Disable button to prevent multiple submissions
  submitBtn.disabled = true;
  submitBtn.textContent = "Sending...";

  // EmailJS parameters
  const templateParams = {
    name: name,
    email: email,
    subject: subject,
    message: message, // Replace with your email address
  };

  const serviceId = "service_z8chcra";
  const templateId = "template_wivdpyk";
  emailjs
    .send(serviceId, templateId, templateParams)
    .then(() => {
      statusElement.textContent = "Message sent successfully!";
      statusElement.className = " text-success text-center mt-4";
      statusElement.style.display = "block";
      // Clear form
      document.getElementById("name").value = "";
      document.getElementById("email").value = "";
      document.getElementById("subject").value = "";
      document.getElementById("message").value = "";

      setTimeout(() => {
        statusElement.style.display = "none";
      }, 5000);
    })
    .catch((error) => {
      statusElement.textContent = `Failed to send message: ${
        error.text || "Unknown error"
      }. Please try again.`;
      statusElement.className = "text-danger text-center mt-4";
      statusElement.style.display = "block";

      setTimeout(() => {
        statusElement.style.display = "none";
      }, 5000);
    })
    .finally(() => {
      // Re-enable button
      submitBtn.disabled = false;
      submitBtn.textContent = "Send";
    });
}

// lenis js for smooth scroll

const lenis = new Lenis();
function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}

requestAnimationFrame(raf);
// liquid glass displacement effect

// current year in roman numerals
function toRoman(year) {
  const lookup = {
    M: 1000,
    CM: 900,
    D: 500,
    CD: 400,
    C: 100,
    XC: 90,
    L: 50,
    XL: 40,
    X: 10,
    IX: 9,
    V: 5,
    IV: 4,
    I: 1,
  };
  let roman = "";
  for (let key in lookup) {
    while (year >= lookup[key]) {
      roman += key;
      year -= lookup[key];
    }
  }
  return roman;
}
const currentYear = new Date().getFullYear();
console.log(toRoman(currentYear));

// Set the current year in the footer
document.getElementById("currentYear").textContent = toRoman(currentYear);
