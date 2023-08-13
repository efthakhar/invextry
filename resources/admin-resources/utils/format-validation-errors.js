export default function formatValidationErrors(erros) {
    Object.keys(erros).forEach((key) => {
        erros[key] = erros[key].toString();
    });

    return erros;
}
