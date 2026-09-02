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

// liquid glass displacement effect