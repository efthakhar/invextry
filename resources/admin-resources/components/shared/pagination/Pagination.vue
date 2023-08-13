<script setup>
import { ref } from "vue";

const props = defineProps({
    total_pages: Number,
    current_page: Number,
    per_page: Number,
});

let item_per_page = ref(props.per_page);

const emit = defineEmits(["perPageChange", "pageChange"]);

function pageChange(perpage) {
    item_per_page.value = perpage;
    emit("perPageChange", parseInt(perpage));
}
</script>

<template>
    <div class="pagination-perpage-container d-flex flex-wrap my-4">
        <div class="select_per_page">
            <select
                v-model="item_per_page"
                @change="pageChange(item_per_page)"
                class="form-select form-select-sm text-uppercase"
            >
                <option value="5">5 per page</option>
                <option value="10">10 per page</option>
                <option value="20">20 per page</option>
                <option value="30">30 per page</option>
                <option value="40">40 per page</option>
                <option value="50">50 per page</option>
            </select>
        </div>

        <ul
            class="ms-auto pagination pagination-primary"
            v-if="total_pages > 1"
        >
            <!-- prev -->
            <li
                @click="$emit('pageChange', parseInt(current_page) - 1)"
                class="page-item us-none cursor-pointer"
                :class="current_page == 1 ? 'd-none' : ''"
            >
                <span class="page-link"> Prev</span>
            </li>

            <!-- 1st page  -->
            <li
                @click="$emit('pageChange', 1)"
                class="page-item us-none cursor-pointer"
                :class="
                    current_page == 1 ||
                    parseInt(current_page) - 1 == 1 ||
                    parseInt(current_page) - 2 == 1
                        ? 'd-none'
                        : ''
                "
            >
                <span class="page-link"> 1 </span>
            </li>

            <span>&nbsp; </span>
            <span>&nbsp; </span>

            <!-- current-2  -->
            <li
                @click="$emit('pageChange', parseInt(current_page) - 2)"
                class="page-item us-none cursor-pointer"
                :class="
                    current_page == 1 || parseInt(current_page) - 1 == 1
                        ? 'd-none'
                        : ''
                "
            >
                <span class="page-link"> {{ current_page - 2 }} </span>
            </li>

            <!-- current-1  -->
            <li
                @click="$emit('pageChange', parseInt(current_page) - 1)"
                class="page-item us-none cursor-pointer"
                :class="current_page == 1 ? 'd-none' : ''"
            >
                <span class="page-link"> {{ current_page - 1 }} </span>
            </li>

            <!-- current -->
            <li class="page-item us-none cursor-pointer active">
                <span class="page-link" href="#">{{ current_page }}</span>
            </li>

            <!-- current+1  -->
            <li
                @click="$emit('pageChange', parseInt(current_page) + 1)"
                class="page-item us-none cursor-pointer"
                :class="current_page == total_pages ? 'd-none' : ''"
            >
                <span class="page-link"> {{ parseInt(current_page) + 1 }}</span>
            </li>

            <!-- current+2  -->
            <li
                @click="$emit('pageChange', parseInt(current_page) + 2)"
                class="page-item us-none cursor-pointer"
                :class="
                    parseInt(current_page) == total_pages ||
                    parseInt(current_page) + 1 == total_pages
                        ? 'd-none'
                        : ''
                "
            >
                <span class="page-link">
                    {{ parseInt(current_page) + 2 }}
                </span>
            </li>

            <span>&nbsp; </span>
            <span>&nbsp; </span>
            <!-- last page  -->
            <li
                @click="$emit('pageChange', parseInt(total_pages))"
                class="page-item us-none cursor-pointer"
                :class="
                    current_page == total_pages ||
                    parseInt(current_page) + 1 == total_pages ||
                    parseInt(current_page) + 2 == total_pages
                        ? 'd-none'
                        : ''
                "
            >
                <span class="page-link"> {{ parseInt(total_pages) }} </span>
            </li>

            <!-- next -->
            <li
                @click="$emit('pageChange', parseInt(current_page) + 1)"
                class="page-item us-none cursor-pointer"
                :class="current_page == total_pages ? 'd-none' : ''"
            >
                <span class="page-link"> next </span>
            </li>
        </ul>
    </div>
</template>
