<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">
                {{ editing ? 'Editar Ticket' : 'Crear Nuevo Ticket' }}
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="4"
                            required
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <!-- Priority -->
                    <div>
                        <InputLabel for="priority" value="Prioridad" />
                        <select
                            id="priority"
                            v-model="form.priority"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                        >
                            <option value="low">Baja</option>
                            <option value="medium">Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                        <InputError :message="form.errors.priority" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <InputLabel for="status" value="Estado" />
                        <select
                            id="status"
                            v-model="form.status"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                        >
                            <option value="open">Abierto</option>
                            <option value="in_progress">En Progreso</option>
                            <option value="resolved">Resuelto</option>
                            <option value="closed">Cerrado</option>
                        </select>
                        <InputError :message="form.errors.status" class="mt-2" />
                    </div>

                    <!-- Device -->
                    <div class="md:col-span-2">
                        <InputLabel for="device_id" value="Dispositivo (Opcional)" />
                        <select
                            id="device_id"
                            v-model="form.device_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                            <option value="">Seleccionar dispositivo</option>
                            <option v-for="device in devices" :key="device.id" :value="device.id">
                                {{ device.name }} ({{ device.ip_address }})
                            </option>
                        </select>
                        <InputError :message="form.errors.device_id" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
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
import { ref, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import type { Ticket, NetworkDevice } from '@/types';

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

watch(() => props.show, (newShow) => {
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
});
</script>
