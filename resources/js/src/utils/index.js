// eslint-disable-next-line object-curly-newline
import { getCurrentInstance, reactive, toRefs, watch } from '@vue/composition-api'

// Thanks: https://medium.com/better-programming/reactive-vue-routes-with-the-composition-api-18c1abd878d1
export const useRouter = () => {
  const vm = getCurrentInstance().proxy

  const state = reactive({
    route: vm.$route,
  })

  watch(
    () => vm.$route,
    r => {
      state.route = r
    },
  )

  return { ...toRefs(state), router: vm.$router }
}

export const _ = null

export const getErrorFields = (errorResponse) => {
  console.log(errorResponse);
  if (!errorResponse) return [];

  const { errors } = errorResponse;
  if (!errors) return [];

  const errorFields = [];
  for (const property in errors) {
    const propertiesErrors = errors[property];
    if (Array.isArray(propertiesErrors)) {
      for (const item of propertiesErrors) {
        errorFields.push(item)
      }
    }
  }

  return errorFields;
}

export const toQueryParams = (model) => {
  if (!(typeof model === 'object')) {
    return ''
  }

  let queryString = '?'
  let count = 0;

  for (const property in model) {
    if (model[property] !== undefined && model[property] !== null && model[property] !== "") {

      if (Array.isArray(model[property]) && model[property].length === 0) {
        break
      }

      if (count > 0) {
        queryString += '&'
      }
      queryString += `${property}=${model[property]}`

      count++;
    }

  }

  return queryString
}
