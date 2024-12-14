const inputs = document.querySelectorAll(".input");


function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
	}
}


inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});


  const ctx = document.getElementById('barChart').getContext('2d');

  // Datos para la gráfica
  const data = {
	  labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'], // Etiquetas
	  datasets: [{
		  label: 'Personas',
		  data: [50, 100, 75, 125, 90], // Datos de ejemplo
		  backgroundColor: [
			  'rgba(255, 99, 132, 0.2)',
			  'rgba(54, 162, 235, 0.2)',
			  'rgba(255, 206, 86, 0.2)',
			  'rgba(75, 192, 192, 0.2)',
			  'rgba(153, 102, 255, 0.2)'
		  ],
		  borderColor: [
			  'rgba(255, 99, 132, 1)',
			  'rgba(54, 162, 235, 1)',
			  'rgba(255, 206, 86, 1)',
			  'rgba(75, 192, 192, 1)',
			  'rgba(153, 102, 255, 1)'
		  ],
		  borderWidth: 1
	  }]
  };

  // Configuración de la gráfica
  const config = {
	  type: 'bar', // Tipo de gráfica
	  data: data,
	  options: {
		  scales: {
			  y: {
				  beginAtZero: true
			  }
		  }
	  }
  };

  // Crear la gráfica
  const barChart = new Chart(ctx, config);