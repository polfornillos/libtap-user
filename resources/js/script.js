document.addEventListener("DOMContentLoaded", function () {
    // Check if the error message is defined and not empty
    if (typeof errorMessage !== "undefined" && errorMessage) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: errorMessage,
        });
    }

    // User type change event
    document.getElementById("userType").addEventListener("change", function () {
        var userType = this.value;
        var studentEmployeeNumberContainer = document.getElementById(
            "studentEmployeeNumberContainer"
        );
        var studentEmployeeNumberInput = document.getElementById(
            "studentEmployeeNumber"
        );

        if (userType === "non-teaching") {
            studentEmployeeNumberContainer.style.display = "none";
            studentEmployeeNumberInput.disabled = true;
        } else {
            studentEmployeeNumberContainer.style.display = "block";
            studentEmployeeNumberInput.disabled = false;
        }
    });
});
