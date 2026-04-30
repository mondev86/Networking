<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6">
            <h2 class="mb-6 text-lg font-medium text-gray-900">
                {{ editing ? 'Editar Ticket' : 'Crear Nuevo Ticket' }}
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <InputLabel for="title" value="Título" />
                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <InputLabel for="description" value="Descripción" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            rows="4"
                            required
                        ></textarea>
                        <InputError
                            :message="form.errors.description"
                            class="mt-2"
                        />
                    </div>

                    <!-- Priority -->
                    <div>
                        <InputLabel for="priority" value="Prioridad" />
                        <select
                            id="priority"
                            v-model="form.priority"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="low">Baja</option>
                            <option value="medium">Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                        <InputError
                            :message="form.errors.priority"
                            class="mt-2"
                        />
                    </div>

                    <!-- Status -->
                    <div>
                        <InputLabel for="status" value="Estado" />
                        <select
                            id="status"
                            v-model="form.status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="open">Abierto</option>
                            <option value="in_progress">En Progreso</option>
                            <option value="resolved">Resuelto</option>
                            <option value="closed">Cerrado</option>
                        </select>
                        <InputError
                            :message="form.errors.status"
                            class="mt-2"
                        />
                    </div>

                    <!-- Device -->
                    <div class="md:col-span-2">
                        <InputLabel
                            for="device_id"
                            value="Dispositivo (Opcional)"
                        />
                        <select
                            id="device_id"
                            v-model="form.device_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Seleccionar dispositivo</option>
                            <option
                                v-for="device in devices"
                                :key="device.id"
                                :value="device.id"
                            >
                                {{ device.name }} ({{ device.ip_address }})
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.device_id"
                            class="mt-2"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <SecondaryButton @click="closeModal" class="mr-3">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton :disabled="form.processing">
                        {{ editing ? 'Actualizar' : 'Crear' }} Ticket
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import type { NetworkDevice, Ticket } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Props {
    show: boolean;
    ticket?: Ticket | null;
    devices: NetworkDevice[];
}

const props = defineProps<Props>();

const emit = defineEmits<{
    close: [];
}>();

const editing = ref(false);

const form = useForm({
    title: '',
    description: '',
    priority: 'medium',
    status: 'open',
    device_id: '',
});

const closeModal = () => {
    form.reset();
    emit('close');
};

const submit = () => {
    if (editing.value && props.ticket) {
        form.put(route('tickets.update', props.ticket.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('tickets.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

watch(
    () => props.show,
    (newShow) => {
        if (newShow && props.ticket) {
            editing.value = true;
            form.title = props.ticket.title;
            form.description = props.ticket.description || '';
            form.priority = props.ticket.priority;
            form.status = props.ticket.status;
            form.device_id = props.ticket.device?.id.toString() || '';
        } else if (newShow) {
            editing.value = false;
            form.reset();
        }
    },
);
</script>
