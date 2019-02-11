const state = {
    creado: false
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
