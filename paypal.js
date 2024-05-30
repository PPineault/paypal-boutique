let totalAmountString = document.getElementById("total_price").innerText.trim();
let totalAmount = parseFloat(
  totalAmountString.replace("$", "").replace(/,/g, "")
);

let totalCents = Math.round(totalAmount * 100);
let total = parseInt(totalCents);

paypal
  .Buttons({
    style: {
      layout: "vertical",
      color: "silver",
      shape: "rect",
      label: "paypal",
      disableMaxWidth: "true",
    },
    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [
          {
            amount: {
              value: totalAmount,
            },
          },
        ],
      });
    },
    onApprove: function (data, actions) {
      return actions.order.capture().then(function (details) {
        window.location.replace("?msg=success");
      });
    },
    onCancel: function (data) {
      window.location.replace("?msg=failed");
    },
  })
  .render("#paypal-button");
