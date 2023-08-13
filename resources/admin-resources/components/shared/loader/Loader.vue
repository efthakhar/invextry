<script setup></script>

<template>
    <div class="loader_container">
        <div class="loader"></div>
    </div>
</template>

<style scoped>
.loader_container {
    width: 100%;
    min-width: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 100px 0px 50px;
}

.loader {
    color: #3485f05d;
    position: relative;
    font-size: 17px;
    background: #3485f05d;
    animation: escaleY 0.7s infinite ease-in-out;
    width: 1em;
    height: 4em;
    animation-delay: -0.16s;
}
.loader:before,
.loader:after {
    content: "";
    position: absolute;
    top: 0;
    left: 2em;
    background: #3485f05d;
    width: 1em;
    height: 4em;
    animation: escaleY 0.7s infinite ease-in-out;
}
.loader:before {
    left: -2em;
    animation-delay: -0.32s;
}

@keyframes escaleY {
    0%,
    80%,
    100% {
        box-shadow: 0 0;
        height: 4em;
    }
    40% {
        box-shadow: 0 -2em;
        height: 5em;
    }
}
</style>
