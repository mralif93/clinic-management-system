/**
 * Real-time Updates using Server-Sent Events (SSE)
 * Falls back to polling if SSE not available
 */

window.RealtimeUpdates = {
    eventSource: null,
    pollingInterval: null,
    listeners: new Map(),

    /**
     * Initialize real-time updates
     * @param {string} endpoint - SSE endpoint
     * @param {object} options - Options
     */
    init(endpoint, options = {}) {
        const defaultOptions = {
            fallbackPolling: true,
            pollingInterval: 30000, // 30 seconds
            autoReconnect: true,
            reconnectDelay: 5000,
            ...options
        };

        // Try SSE first
        if (typeof EventSource !== 'undefined') {
            this.initSSE(endpoint, defaultOptions);
        } else if (defaultOptions.fallbackPolling) {
            this.initPolling(endpoint, defaultOptions);
        }
    },

    /**
     * Initialize Server-Sent Events
     * @param {string} endpoint - SSE endpoint
     * @param {object} options - Options
     */
    initSSE(endpoint, options) {
        try {
            this.eventSource = new EventSource(endpoint);

            this.eventSource.onopen = () => {
                console.log('Real-time connection established');
                this.dispatchEvent('connected');
            };

            this.eventSource.onerror = () => {
                console.error('Real-time connection error');
                this.dispatchEvent('error');
                
                if (options.autoReconnect) {
                    setTimeout(() => {
                        this.initSSE(endpoint, options);
                    }, options.reconnectDelay);
                }
            };

            this.eventSource.onmessage = (event) => {
                try {
                    const data = JSON.parse(event.data);
                    this.handleMessage(data);
                } catch (e) {
                    console.error('Failed to parse SSE message:', e);
                }
            };

            // Listen for custom event types
            this.eventSource.addEventListener('update', (event) => {
                try {
                    const data = JSON.parse(event.data);
                    this.handleMessage(data);
                } catch (e) {
                    console.error('Failed to parse SSE update:', e);
                }
            });

        } catch (e) {
            console.error('Failed to initialize SSE:', e);
            if (options.fallbackPolling) {
                this.initPolling(endpoint, options);
            }
        }
    },

    /**
     * Initialize polling fallback
     * @param {string} endpoint - Polling endpoint
     * @param {object} options - Options
     */
    initPolling(endpoint, options) {
        const poll = () => {
            fetch(endpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                this.handleMessage(data);
            })
            .catch(error => {
                console.error('Polling error:', error);
            });
        };

        // Initial poll
        poll();

        // Set up interval
        this.pollingInterval = setInterval(poll, options.pollingInterval);
    },

    /**
     * Handle incoming message
     * @param {object} data - Message data
     */
    handleMessage(data) {
        if (data.type && this.listeners.has(data.type)) {
            const callbacks = this.listeners.get(data.type);
            callbacks.forEach(callback => {
                try {
                    callback(data);
                } catch (e) {
                    console.error('Error in real-time callback:', e);
                }
            });
        }

        // Dispatch generic update event
        this.dispatchEvent('update', data);
    },

    /**
     * Subscribe to event type
     * @param {string} eventType - Event type
     * @param {Function} callback - Callback function
     */
    subscribe(eventType, callback) {
        if (!this.listeners.has(eventType)) {
            this.listeners.set(eventType, []);
        }
        this.listeners.get(eventType).push(callback);
    },

    /**
     * Unsubscribe from event type
     * @param {string} eventType - Event type
     * @param {Function} callback - Callback function to remove
     */
    unsubscribe(eventType, callback) {
        if (this.listeners.has(eventType)) {
            const callbacks = this.listeners.get(eventType);
            const index = callbacks.indexOf(callback);
            if (index > -1) {
                callbacks.splice(index, 1);
            }
        }
    },

    /**
     * Dispatch custom event
     * @param {string} eventName - Event name
     * @param {object} data - Event data
     */
    dispatchEvent(eventName, data = {}) {
        const event = new CustomEvent(`realtime:${eventName}`, { detail: data });
        document.dispatchEvent(event);
    },

    /**
     * Close connection
     */
    close() {
        if (this.eventSource) {
            this.eventSource.close();
            this.eventSource = null;
        }

        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }
};

// Auto-initialize if data-realtime attribute is present
document.addEventListener('DOMContentLoaded', () => {
    const realtimeElements = document.querySelectorAll('[data-realtime]');
    realtimeElements.forEach(element => {
        const endpoint = element.dataset.realtime;
        RealtimeUpdates.init(endpoint);
    });
});

