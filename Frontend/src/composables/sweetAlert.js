import Swal from 'sweetalert2';
import { ref } from 'vue';

export function sweetalert() {
    const alert = ref(null);
    const ShowLoading = () => {
        // Dentro de la función ShowLoading, creamos una instancia de SweetAlert2 y la almacenamos en la variable loadingAlert.
        const loadingAlert = Swal.fire({
          title: 'Cerrando Sesión',
          html: '<div class="spinner-border text-primary mt-2 mb-2"></div>',
          showConfirmButton: false,
          allowOutsideClick: false, 
        });
      
        // Definimos una función interna llamada CloseLoading que cerrará la instancia de SweetAlert2.
        const CloseLoading = () => {
          Swal.close(); // Cerramos la instancia de SweetAlert2
        };
      
        // Devolvemos la función CloseLoading para que pueda ser utilizada fuera de la función ShowLoading.
        return CloseLoading;
      };
    const successAlert = (title, text) => {
        Swal.fire({
            title: title,
            text: text,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    };
    const errorAlert = (title, text) => {
        Swal.fire({
            title: title,
            text: text,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    };
    const confirmAlert = async (title, text) => {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            return result.isConfirmed;
        });
    };
    const showAlert = (title, text) => {
        Swal.fire({
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    };

    return { alert, successAlert, errorAlert, confirmAlert, ShowLoading, showAlert};
}