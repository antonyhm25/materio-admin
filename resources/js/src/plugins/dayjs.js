import dayjs from 'dayjs'

import localizedFormat from 'dayjs/plugin/localizedFormat'
import 'dayjs/locale/es'

import relativeTime from 'dayjs/plugin/relativeTime'
import updateLocale from 'dayjs/plugin/updateLocale'

dayjs.extend(localizedFormat)
dayjs.extend(relativeTime)
dayjs.extend(updateLocale)

dayjs.locale('es')

dayjs.updateLocale('es', {
  weekStart: 0
})

export default dayjs
