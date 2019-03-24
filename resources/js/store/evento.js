const state = {
    creado: false,
    actividades: [
        {
            nombre: "Lección",
            materia: "Algebra Lineal",
            descripcion: "Lección acerca de matrices y transformaciones",
            fecha: "2019-09-02",
            hora: "12:30",
            aula: "101"
        },
        {
            nombre: "Deber",
            materia: "Investigacion de Operaciones",
            descripcion: "Ejercicios resolución de ecuaciones método simplex",
            fecha: "2019-09-02",
            hora: "18:30",
            aula: "120"
        },
        {
            nombre: "Deber",
            materia: "Investigacion de Operaciones",
            descripcion: "Ejercicios resolución de ecuaciones método simplex",
            fecha: "2019-09-02",
            hora: "18:30",
            aula: "120"
        },
        {
            nombre: "Deber",
            materia: "Investigacion de Operaciones",
            descripcion: "Ejercicios resolución de ecuaciones método simplex",
            fecha: "2019-09-02",
            hora: "18:30",
            aula: "120"
        },
        {
            nombre: "Deber",
            materia: "Investigacion de Operaciones",
            descripcion: "Ejercicios resolución de ecuaciones método simplex",
            fecha: "2019-09-02",
            hora: "18:30",
            aula: "120"
        }
    ]
};


const mutations = {
    cambiar_estado(state, estado) {
        state.creado = estado;
    }
};

const actions = {
    toogle_action({commit}, estado) {
        commit("cambiar_estado", estado);
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
