import Swal from 'sweetalert2';

export const DELETED_MESSAGE = 'Eliminado correctamente';
export const UPDATED_MESSAGE = 'Actualizado correctamente';
export const CREATED_MESSAGE = 'Creado correctamente';
export const LOADED_MESSAGE  = 'Cargado correctamente';
export const ERROR_MESSAGE   = 'Ocurrió un error, intenta de nuevo';

export const alertLoading = (text) => {
    return    Swal.fire({
        title: 'Espere un momento..!',
        html: text,
        allowOutsideClick: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
    });
}

export const showCreatedMessage = () => {
    return Swal.fire(
        'Correcto',
        CREATED_MESSAGE,
        'success'
    );
}

export const showUpdatedMessage = () => {
    return Swal.fire(
        'Correcto',
        UPDATED_MESSAGE,
        'success'
    );
}

export const showLoadedMessage = () => {
    return Swal.fire(
        'Correcto',
        LOADED_MESSAGE,
        'success'
    );
}

export const showDeletedMessage = () => {
    return Swal.fire(
        'Correcto',
        DELETED_MESSAGE,
        'success'
    );
}

export const showErrorMessage = () => {
    return Swal.fire(
        'Ocurrió un error',
        'Por favor intente de nuevo',
        'error'
    );
}

export const showErrorMessageWithMessage = (message) => {
    return Swal.fire(
        'Ocurrió un error',
        message,
        'error'
    );
}

export const failedPassword = (message) => {
    return Swal.fire(
        'Clave incorrecto',
        message,
        'error'
    );
}

export const showMessage = (title, message, type) => {
    return Swal.fire(
        title,
        message,
        type
    );
}
export const showConfirmDeleteMessage = () => {
    return Swal.fire({
        title: 'Eliminar',
        html: '¿Desea eliminar el elemento?',
        type: 'warning',
        icon: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
    });
    // Example of then
    /*.then((result) => {
        if (result.value) {

        }
    });*/
}
export const showConfirmAnularMessage = () => {
    return Swal.fire({
        title: 'Anular',
        html: '¿Desea anular el elemento?',
        type: 'warning',
        icon: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, anular',
        cancelButtonText: 'Cancelar'
    });
}

export const showConfirmDisableMessage = () => {
    return Swal.fire({
        title: 'Inhabilitar',
        html: '¿Desea inhabilitar el elemento?',
        type: 'warning',
        icon: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, inhabilitar',
        cancelButtonText: 'Cancelar'
    });
}

export const showConfirmMessage = (title,html,type) => {
    return Swal.fire({
        title: title,
        html: html,
        type: type,
        icon: type,
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar'
    });
    // Example of then
    /*.then((result) => {
        if (result.value) {

        }
    });*/
}

export const showToastMessage = (title,position, time = 1500) => {
    const Toast = Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: time,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    return Toast.fire({
        icon: 'success',
        title: title
    });
}

export const showToastErrorMessage = (title,position, time = 1500) => {
    const Toast = Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: time,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    return Toast.fire({
        icon: 'error',
        title: title
    });
}

export const showSeatBlock = ()=> {
    return Swal.fire({

        title: '',
        html: 'El asiento fue bloqueado por otro usuario',
        type: 'error',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Solicitar Liberación',
        cancelButtonText: 'Cancelar'
    });
}
export const showSeatBook = ()=> {
    return Swal.fire(
        '',
        'El asiento fue reservado',
        'warning'
    );
}
export const showSeatSell = ()=> {
    return Swal.fire(
        '',
        'El asiento fue vendido',
        'error'
    );
}
export const showSelectModalidad = ()=> {
    return Swal.fire(
        '',
        'Seleccione la modalidad de compra',
        'warning'
    );
}
export const showUniqueReserveSeat = ()=> {
    return Swal.fire(
        '',
        'Asiento reservado',
        'success'
    );
}
export const showMultipleReserveSeat = ()=> {
    return Swal.fire(
        '',
        'Asientos reservados',
        'success'
    );
}
export const showNoReservedSeats = ()=> {
    return Swal.fire(
        '',
        'No hay asientos reservados',
        'error'
    );
}
export const showNoTickets = ()=> {
    return Swal.fire(
        '',
        'No hay boletos',
        'error'
    );
}
export const confirmDeleteReservation = () => {
    return Swal.fire({
        title: 'Eliminar',
        html: '¿Desea eliminar la reserva?',
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
    });
    // Example of then
    /*.then((result) => {
        if (result.value) {

        }
    });*/
}
export const confirmDeleteDeparture = () => {
    return Swal.fire({
        title: 'Eliminar',
        html: '¿Está seguro de eliminar la salida?',
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
    });
    // Example of then
    /*.then((result) => {
        if (result.value) {

        }
    });*/
}
export const showSuccessDeleteTicket = () => {
    return Swal.fire(
        'Correcto',
        'Boleto anulado',
        'success'
    );
}
export const showSuccessReservation = () => {
    return Swal.fire(
        'Correcto',
        'Reserva anulada',
        'success'
    );
}
export const showInvalidPassword = ()=> {
    return Swal.fire(
        '',
        'Contraseña incorrecta',
        'error'
    );
}
export const showSuccessChangePrice = () => {
    return Swal.fire(
        'Correcto',
        'Cambio ralizado',
        'success'
    );
}
export const showErrorCapacity = ()=> {
    return Swal.fire(
        '',
        'Se excedió la cantidad de asientos disponibles',
        'error'
    );
}
export const showSuccessDeleteReservations = () => {
    return Swal.fire(
        'Correcto',
        'Reservas anuladas',
        'success'
    );
}

export const confirmGetOuthDeparturePage = () => {
    return Swal.fire({
        title: '¿Desea salir del sitio?',
        html: 'Sus asientos bloqueados no serán guardados',
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar'
    });
    // Example of then
    /*.then((result) => {
        if (result.value) {

        }
    });*/
}


/*added 21/05/2020 by Fabrizio*/
export const showWarningChangeBus = (listNotifications)=> {
    let text = ''
    listNotifications.forEach((notification)=>{
        text += `- ${notification} <br>`
    })
    return Swal.fire(
        '',
        `${text}`,
        'warning'
    );
}

// added 28/05
export const showWarningSeat = (text)=> {
    return Swal.fire(
        '',
        `${text}`,
        'warning'
    );
}
export const showChangeSeatSuccessful = ()=> {
    return Swal.fire(
        '',
        'Se realizo el traslado',
        'success'
    );
}

export const confirmChangeBus = () => {
    return Swal.fire({
        title: '¿Desea realiza el cambio de bus?',
        html: 'Recuerde que este cambio no se puede deshacer',
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar'
    });
}
export const showSuccessChangeBus = () => {
    return Swal.fire(
        'Correcto',
        'Se realizo el cambio de bus de manera correcta',
        'success'
    );
}