<?php
// Função para limpar o telefone, removendo caracteres não numéricos
function limparTelefone($telefone) {
    return preg_replace("/\D/", "", $telefone);
}

// Função para gerar o link do WhatsApp
function gerarLinkWhatsApp($telefone) {
    return "https://wa.me/55" . limparTelefone($telefone);
}
?>
