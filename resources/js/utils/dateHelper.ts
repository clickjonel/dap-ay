import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(utc)
dayjs.extend(timezone)

export const formatDate = (date: string | Date | null) => {
    if (!date) return null
    return dayjs(date).tz('Asia/Manila').format('YYYY-MM-DD')
}