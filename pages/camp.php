<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Campify - Camps</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <script
      src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
      crossorigin="anonymous"
    ></script>
    <style>* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Nunito Sans", sans-serif;
}

html,
body {
  position: relative;
  width: 100%;
  height: 100%;
  scroll-behavior: smooth;
}

.navbar {
  position: static;
  background-color: grey;
  color: white;
  padding-top: 10px;
  padding-bottom: 10px;
}

.camp-info {
  margin-top: 60px;
}

.camp-name {
  font-weight: 700;
  color: #f2681d;
  font-size: 30px;
}

.main {
  /* max-width: 1200px; */
  margin-inline: auto;
}

.bento {
  margin-block: 1rem;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: repeat(5, 1fr);
  height: 30rem;
  gap: 1rem;
}
.bento-card {
  inline-size: 100%;
  background: center / cover;
  border-radius: 1rem;
}
.bento-card:nth-child(1) {
  grid-column: span 2;
  grid-row: span 5;
}
.bento-card:nth-child(2),
.bento-card:nth-child(5) {
  grid-row: span 2;
}
.bento-card:nth-child(3),
.bento-card:nth-child(4) {
  grid-row: span 3;
}
.bento-hover .bento-card {
  transition: 500ms;
}
.bento-hover .bento-card:hover {
  scale: 1.01;
}
.bento-hover:hover .bento-card:not(:hover) {
  filter: saturate(50%) brightness(50%);
}
.bento-perspective {
  transform: perspective(1000px) rotateY(12deg) scaleX(1.372);
  transform-origin: left;
}

#mybox1id {
  display: none;
}
</style>


    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="camp.css" />
  </head>
 


  <body>
    <?php 
    include("../components/header.php");
    ?>

    <div class="container d-flex justify-content-center text-center camp-info">
      <div>
        <h4 class="camp-name">Him Camp</h4>
        <p class="location">Manali, H.P</p>
      </div>
    </div>

    <main class="main">
      <div class="bento container">
        <div
          class="bento-card"
          style="background-image: url('../assets/camp/pexels-pixabay-273003.jpg')"
        ></div>
        <div
          class="bento-card"
          style="background-image: url('../assets/hero/hero-2.jpg')"
        ></div>
        <div
          class="bento-card"
          style="
            background-image: url('../assets/camp/pexels-tobiasbjorkli-1559402.jpg');
          "
        ></div>
        <div
          class="bento-card"
          style="
            background-image: url('../assets/camp/pexels-tracehudson-2724664.jpg');
          "
        ></div>
        <div
          class="bento-card"
          style="
            background-image: url('../assets/camp/pexels-vishal-amin-302220-910307.jpg');
          "
        ></div>
      </div>
    </main>

    <section class="camp-data">
      <div class="container">
        <h4>Lorem</h4>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo
          voluptates praesentium est id incidunt atque ipsa alias quam nemo
          perspiciatis accusantium obcaecati eius quis, ducimus, cumque
          consequatur autem dignissimos at! Facere quo voluptas soluta veritatis
          expedita doloribus consequatur necessitatibus qui iure! Nemo
          repudiandae sunt dolore nihil, natus maiores expedita asperiores
          ipsum, porro nisi explicabo ratione facere ducimus eligendi
          praesentium! Nesciunt? Voluptatibus voluptatem recusandae odit<span
            id="span1"
            >...</span
          >
          <span class="mybox1" id="mybox1id">
            incidunt in. Unde non consequuntur, animi fugit esse eveniet quo
            sunt ullam architecto saepe nulla illo iusto laboriosam? Enim
            similique, sit vitae possimus consequatur mollitia reprehenderit?
            Impedit natus iusto cum, nobis itaque, dolores reiciendis
            praesentium tempore in consectetur molestias officia autem!
            Obcaecati quis accusamus libero hic? Optio in vero rerum fuga!
            Accusantium ducimus voluptatibus quaerat repudiandae. Dignissimos
            quasi ullam modi odio, culpa animi deserunt omnis repellat voluptas
            molestias repellendus obcaecati sapiente cumque velit facilis natus
            ab illum quod odit eveniet. Obcaecati aliquid non sit excepturi
            exercitationem! Fuga ipsum reiciendis, delectus dolorem iure quas
            ipsa. Doloremque quam eos necessitatibus. Enim obcaecati ullam magni
            eaque amet nobis earum repellat maxime in exercitationem. Facere
            repellendus explicabo modi? Animi, rem. Velit cum quibusdam, odit a,
            exercitationem voluptas debitis veritatis optio recusandae adipisci
            reprehenderit et culpa mollitia? Illo, debitis? Magni ratione aut
            sed aliquam deserunt veritatis, laudantium perferendis ipsum
            expedita rerum? Eveniet ea et asperiores molestiae magni natus rem,
            aliquid facilis voluptatibus atque facere animi excepturi quae
            doloremque quia vero ab corporis sunt dolorem repudiandae optio
            expedita officiis illo. Ad, quod! Est recusandae commodi aut illo
            exercitationem nostrum sit odio ipsam aliquid facere debitis neque
            pariatur doloribus quidem et, aliquam magni possimus minus
            consequuntur distinctio harum eum itaque odit. Molestiae, ipsa!
            Aliquam corporis nobis quidem voluptatum atque id velit repellendus
            dolorum, impedit quaerat consectetur similique quas quo veniam
            deserunt eos neque ut vel doloribus alias aperiam. Commodi ratione
            excepturi facere molestias! Molestiae sint, est quibusdam amet optio
            perspiciatis excepturi ducimus eos consequatur quidem id corrupti
            atque, ipsum quisquam earum magni ex officiis doloremque. Inventore
            facere ad voluptatibus maxime beatae, optio magnam. Nulla, sit
            pariatur neque totam mollitia perferendis quae earum, eius sunt
            dolore possimus nam provident modi consequuntur autem consequatur.
            Vero provident deserunt, dolor tenetur veniam officia facere fugiat
            eaque et. Totam aliquid reprehenderit officiis accusamus. Eos odit
            accusamus blanditiis! Expedita itaque quibusdam pariatur, porro
            incidunt eligendi, quas inventore cupiditate totam eum recusandae
            deserunt quae modi! Iusto eligendi dolore eos nemo. Eos magni,
            incidunt voluptate neque fuga possimus blanditiis qui velit vitae
            nostrum impedit tempore vero eveniet esse libero natus odit
            perferendis sed numquam voluptatibus praesentium! Est ducimus iste
            saepe similique. Sit quam voluptatibus error repudiandae
            consequatur, veritatis laudantium nostrum harum. Sapiente dolorem
            quaerat quo! Tempore quasi eligendi facere, quia officia, aliquam
            exercitationem, ipsa fugiat dolores error vel blanditiis aperiam.
            Corrupti! Rem debitis, cumque harum adipisci sunt temporibus sint
            doloremque quae placeat iste ducimus dicta aperiam perspiciatis
            eveniet, veniam, tempora ut a. Laudantium necessitatibus recusandae
            saepe officia adipisci? Veniam, ad aspernatur! Numquam sunt
            consectetur dolores nemo corrupti ratione blanditiis amet, ea
            pariatur non ipsam suscipit assumenda accusamus illo porro
            temporibus ab in fugit sequi perferendis neque minus mollitia natus.
            Adipisci, voluptatem! Porro voluptas eum, necessitatibus amet
            maiores iste modi. Adipisci, saepe distinctio eos quas hic nisi
            necessitatibus autem natus iure cupiditate, tempore aperiam
            praesentium ipsum quisquam labore ex? Tempora, consequatur minus?
            Iure reprehenderit asperiores quam quia, iste maiores cum id
            repellendus, earum dignissimos eaque pariatur beatae voluptates eos,
            tempora ullam fuga. Error sed nihil expedita eveniet neque fugiat
            rerum. Explicabo, odio. Dolorem nobis earum ipsam natus
            reprehenderit optio dicta sapiente unde atque. Reprehenderit
            voluptas, officia nesciunt veniam aliquam sint sapiente! Deleniti
            cum, sequi natus amet recusandae odit eos quia dolorum omnis? Atque
            placeat repellat rerum quos, obcaecati harum optio non ipsam
            dignissimos autem. Doloremque delectus nesciunt deserunt incidunt
            quo accusantium sed ab eos hic! Consequatur magnam ratione
            cupiditate. Quas, velit soluta. Pariatur corporis repudiandae
            doloribus cumque. Earum eius inventore dicta amet, nihil nulla
            doloribus libero dolor natus rem fuga! Dolores dolorem expedita
            consequatur aut optio repudiandae reprehenderit repellendus illo
            voluptatibus libero! Quisquam repudiandae ducimus eos, odit quos
            earum incidunt voluptates numquam illum sint ipsum, suscipit
            eligendi beatae libero necessitatibus deleniti! Nisi nemo numquam a.
            Excepturi quia mollitia ad sequi recusandae delectus! Quis, quae
            explicabo perspiciatis quaerat tenetur consectetur consequatur
            quibusdam? Odit ipsa at ipsam architecto maxime exercitationem enim
            sapiente error et corporis! Ducimus qui et non eos provident,
            facilis dignissimos mollitia. Deleniti eligendi adipisci
            reprehenderit, quidem modi cumque ipsum repellendus molestias culpa
            maiores officia ad temporibus vitae possimus vero recusandae itaque
            illum nihil natus amet earum veniam eveniet unde. Eius, id. Totam,
            placeat magnam quis harum eveniet blanditiis reiciendis ex ullam
            quos iste cumque veniam deleniti. Debitis at, natus reiciendis aut
            quaerat eum deleniti velit explicabo odio, distinctio voluptas
            dignissimos. Omnis. Facilis eius sint pariatur a? Aspernatur non
            repellat modi pariatur accusantium, adipisci qui voluptatum ipsa
            quidem. Repudiandae aliquam blanditiis sit eius necessitatibus autem
            animi, sed facilis non consequatur a eaque! Ipsa molestias non
            provident commodi architecto necessitatibus laboriosam voluptatum
            debitis fuga quae, ea expedita excepturi impedit dignissimos
            doloremque aspernatur praesentium. Officia labore sapiente corrupti
            autem? Aliquid odit ea eaque reiciendis? Perspiciatis officiis
            earum, ad laudantium quam consequatur ex vel tempore dicta ratione
            ipsam, quos non vero cum nemo labore? Deserunt in fuga est eveniet
            sit repellat, iure praesentium iusto dolor. Cumque, sint culpa
            dolore qui vitae temporibus eveniet magni repudiandae omnis ducimus
            odit, saepe dolorem possimus eos velit consequatur porro corrupti,
            facilis numquam repellat amet delectus nihil veritatis ab!
            Doloremque!</span
          >
        </p>
        <button id="mybuttonid" onclick="changeReadMore()">Read More</button>
      </div>
    </section>

    <?php 
    include("../components/footer.php");
    ?>


    <script>
      function changeReadMore() {
        const mycontent = document.getElementById("mybox1id");
        const mybutton = document.getElementById("mybuttonid");
        const span1 = document.getElementById("span1");

        if (
          mycontent.style.display === "none" ||
          mycontent.style.display === ""
        ) {
          mycontent.style.display = "inline";
          span1.style.display = "none";
          mybutton.textContent = "Read Less";
        } else {
          mycontent.style.display = "none";
          mybutton.textContent = "Read More";
          span1.style.display = "inline";
        }
      }
    </script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <script src="script.js"></script>
  </body>
</html>
