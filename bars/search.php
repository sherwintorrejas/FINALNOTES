<style>
    .group {
        display: flex;
        line-height: 28px;
        align-items: center;
        position: relative;
        max-width: 70%;
        float: right;
    }

    .input {
        height: 40px;
        line-height: 28px;
        padding: 0 1rem;
        width: 100%;
        padding-left: 2.5rem;
        border: 2px solid transparent;
        border-radius: 8px;
        outline: none;
        background-color: transparent;
        color: #0d0c22;
        box-shadow: 0 0 3px, 0 0 0 2px #555;
        transition: .3s ease;
    }

    .input::placeholder {
        color: black;
    }

    .icon {
        position: absolute;
        left: 1rem;
        fill: #777;
        width: 1rem;
        height: 1rem;
    }
</style>
<div class="group">
    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24">
        <g>
            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
        </g>
    </svg>
    <input id="searchInput" placeholder="Search" type="search" class="input">
</div>

<script>
   
    document.getElementById("searchInput").addEventListener("input", function() {
        var filter, cards, card, title, text, i, titleText, textContent;
        filter = this.value.toUpperCase();
        cards = document.getElementsByClassName("card");
        for (i = 0; i < cards.length; i++) {
            card = cards[i];
            title = card.getElementsByTagName("h2")[0];
            text = card.getElementsByClassName("card-content")[0];
            titleText = title.textContent || title.innerText;
            textContent = text.textContent || text.innerText;
            if (titleText.toUpperCase().indexOf(filter) > -1 || textContent.toUpperCase().indexOf(filter) > -1) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        }
    });


</script>
