document.addEventListener("DOMContentLoaded", () => {
    const fields = [
        "filipino_male", "resident_alien_male", "non_resident_alien_male",
        "below15_male", "15to17_male", "18to30_male", "above30_male",
        "filipino_female", "resident_alien_female", "non_resident_alien_female",
        "below15_female", "15to17_female", "18to30_female", "above30_female"
    ];

    fields.forEach(field => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input) {
            input.addEventListener("input", calculateTotals);
        }
    });

    function calculateTotals() {
        // Calculate Male Total
        const maleFields = ["filipino_male", "resident_alien_male", "non_resident_alien_male", "below15_male", "15to17_male", "18to30_male", "above30_male"];
        const maleTotal = maleFields.reduce((sum, field) => sum + (parseInt(document.querySelector(`input[name="${field}"]`)?.value || "0", 10)), 0);
        document.querySelector(`input[name="total_male"]`).value = maleTotal;

        // Calculate Female Total
        const femaleFields = ["filipino_female", "resident_alien_female", "non_resident_alien_female", "below15_female", "15to17_female", "18to30_female", "above30_female"];
        const femaleTotal = femaleFields.reduce((sum, field) => sum + (parseInt(document.querySelector(`input[name="${field}"]`)?.value || "0", 10)), 0);
        document.querySelector(`input[name="total_female"]`).value = femaleTotal;

        // Calculate Grand Totals
        const grandTotalFields = {
            grand_total_filipinos: ["filipino_male", "filipino_female"],
            grand_total_resident_alien: ["resident_alien_male", "resident_alien_female"],
            grand_total_non_resident_alien: ["non_resident_alien_male", "non_resident_alien_female"],
            grand_total_below15: ["below15_male", "below15_female"],
            grand_total_15to17: ["15to17_male", "15to17_female"],
            grand_total_18to30: ["18to30_male", "18to30_female"],
            grand_total_above30: ["above30_male", "above30_female"],
            grand_total_all: ["total_male", "total_female"]
        };

        for (const [grandTotalField, relatedFields] of Object.entries(grandTotalFields)) {
            const total = relatedFields.reduce((sum, field) => sum + (parseInt(document.querySelector(`input[name="${field}"]`)?.value || "0", 10)), 0);
            document.querySelector(`input[name="${grandTotalField}"]`).value = total;
        }
    }
});
