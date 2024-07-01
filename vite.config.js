import { defineConfig } from "vite"

import { resolve } from 'path'

export default defineConfig( {
    build: {
        rollupOptions: {
            input: {
                main: resolve(__dirname, './index.html'),
                project: resolve(__dirname, './project/project.html'),
                service: resolve(__dirname, './service/service.html'),
                company: resolve(__dirname, './company/company.html'),
             }
        }
    }
})