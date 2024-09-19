<div class="wrapper">  
  <div class="container_box">
    <div class="question">
      Qué es WoB?
    </div>
    <div class="answercont">
      <div class="answer">
Watch out, Bitcoin! es un evento presencial anual para hablar y debatir sobre Bitcoin, que celebrará en 2024 su tercera edición. Organizado por Decentralized_B y la asociación sin ánimo de lucro Watch out, Freedom!, nuestro objetivo es generar conversaciones sobre los distintos ámbitos que afecta la llegada de Bitcoin (economía, soberanía, tecnología, energía, etc.). Queremos que todo el mundo participe en el debate y, para ello, reunimos de manera presencial a las voces más autorizadas del ecosistema.
      </div>
    </div>
  </div>
  
    <div class="contaicontainer_boxer">
    <div class="question">
¿Dónde y cuándo es el evento? ¿Cómo puedo llegar?
    </div>
    <div class="answercont">
      <div class="answer">
El evento se celebra del 27 al 29 de septiembre en Madrid, en el Espacio Pablo VI. La jornada del viernes está dedicada a talleres, y el sábado y domingo se realizarán los debates y paneles. El auditorio se encuentra en la zona universitaria de Madrid y hay plazas de aparcamiento gratuito en las proximidades. También hay una parada de metro cercana, "Vicente Aleixandre" (antigua Metropolitano).¿Cómo puedo comprar mi entrada? 

      </div>
    </div>
  </div>
  
      <div class="container_box">
    <div class="question">
¿Puedo hacerlo de manera anónima?
    </div>
    <div class="answercont">
      <div class="answer">
Puedes comprar tu entrada con Bitcoin o tarjeta. Para realizar la compra es necesario rellenar los campos de nombre y apellidos (pueden ser pseudónimos o iniciales), y proporcionar una cuenta de correo; no necesitamos tu nombre real. El acceso al evento lo proporcionará el QR de un solo uso enviado con la entrada. En caso de conflicto durante el check-in, se podría solicitar reconfirmar el email con el que se hizo la compra.

      </div>
    </div>
  </div>
 
  <div class="container_box">
    <div class="question">
¿Es posible comprar la entrada como empresa y/o obtener factura?
    </div>
    <div class="answercont">
      <div class="answer">
Sí, una vez realizada la compra, envíanos un email a info@wobitcoin.org.
   
      </div>
    </div>
  </div>
  
  <div class="container_box">
    <div class="question">
¿Se pueden hacer fotos?
    </div>
    <div class="answercont">
      <div class="answer">
Durante el evento se repartirán distintivos para señalizar si se desea o no ser fotografiado.
      </div>
    </div>
  </div>

  <div class="container_box">
    <div class="question">
¿Se pueden devolver las entradas?
    </div>
    <div class="answercont">
      <div class="answer">
Hasta un mes antes del comienzo del evento. Pasada esa fecha, puedes publicar tu entrada en la <a href="Https://help.wobitcoin.org">Red Wob!</a> bajo la categoría de Entradas para encontrar compradores y lo compartiremos en el resto de canales de Wob!. 
      </div>
    </div>
  </div>
  
</div>


<script>
  let question = document.querySelectorAll(".question");

question.forEach(question => {
  question.addEventListener("click", event => {
    const active = document.querySelector(".question.active");
    if(active && active !== question ) {
      active.classList.toggle("active");
      active.nextElementSibling.style.maxHeight = 0;
    }
    question.classList.toggle("active");
    const answer = question.nextElementSibling;
    if(question.classList.contains("active")){
      answer.style.maxHeight = answer.scrollHeight + "px";
    } else {
      answer.style.maxHeight = 0;
    }
  })
})
</script>