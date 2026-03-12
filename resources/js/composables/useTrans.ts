import { usePage } from '@inertiajs/vue3'

type Translations = Record<string, any>

export function useTrans() {
    const page = usePage()
    const translations = page.props.translations as Translations

    function t(key: string): string {
        return key.split('.').reduce(
            (obj: any, i) => obj?.[i],
            translations
        ) ?? key
    }

    return { t }
}
