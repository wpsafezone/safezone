(function ($) {
    'use strict';

    $(function () {

        $(document).on('click', '.paymentModal', function () {
            $('#paymentModal').modal('show')
        });

        $(document).on('click', '.paymentModalClose', function () {
            $('#paymentModal').modal('hide')
        });

        $(document).on('click', '.cancelSubscription', function () {
            $('#cancelSubscription').modal('show')
        });

        $(document).on('click', '.cancelSubscriptionClose', function () {
            $('#cancelSubscription').modal('hide')
        });

        $(document).on('click', '.codeModalClose', function () {
            $('#code-modal').modal('hide')
        });

        if ($('.whitelist_widget').length) {
            whitelist_widget().then(r => {
            });
        }

        if ($('.firewall_widget').length) {
            firewall_widget().then(r => {
            });
        }

        $(document).on('change', 'input[name="ratio-paymentMethod"]', function (e) {
            e.preventDefault();
            $('#choosePrice').html($(this).data('price'));
            $('#chooseInterval').html($(this).data('interval'));
        })

        $(document).on('click', '.custom-notice-dismiss', function(e){
            e.preventDefault();
            const payload = {
                action: 'custom_dismiss_notice',
                security: safezone.token,
            }
            $.post(ajaxurl, payload, function (response) {
                $('.custom-dismiss-notice-wrapper').remove();
            });
        });

        let autoscanning_picker = null;
        const autoscanning_picker_selector = '#autoscanning_date';
        if ($(autoscanning_picker_selector).length) {
            let previousDateTime = $(autoscanning_picker_selector).val();
            $(autoscanning_picker_selector).datetimepicker({
                format: 'Y-m-d h:m',
                minDate: 0,
                onChangeDateTime: function (dp, $input) {
                    const newDateTime = $input.val();
                    if (newDateTime !== previousDateTime) {
                        previousDateTime = newDateTime;

                        $('.splash-screen').addClass('show');
                        const payload = {
                            action: 'update_option',
                            security: safezone.token,
                            payload: {
                                key: 'sz_autoscanning_date',
                                value: newDateTime
                            }
                        };
                        $.post(ajaxurl, payload, function (response) {
                            if (response.success) {
                                toastify('success', response.message);
                                live_settings_board().then(r => {
                                });
                                $('.splash-screen').removeClass('show');
                            } else {
                                toastify('error', response.message);
                            }
                        }).fail(function (xhr, status, error) {
                            toastify('error', status + " : " + error);
                        });
                    }
                }
            });
        }

        if ($('#board').length) {
            live_settings_board().then(r => {
            });
        }

        let selectedIds = [];

        if ($('#anti_spamTable').length) {
            const anti_spam_table = safezone_table('#anti_spamTable', 'get_anti_spam_table', [
                {
                    data: 'ip',
                    render: function (data, type, row) {
                        return '<span class="text-blue-40 shadow-none">' + data + '</span>'
                    },
                },
                {
                    data: 'activity',
                    render: function (data, type, row) {
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<span class="table-main__lang"><img class="table-main__lang-flag" src="/wp-content/plugins/safezone/admin/images/flags/' + data.country_code + '.svg" alt="flag"><span class="table-main__lang-name">' + data.country + '</span></span>';
                    }
                },
                {
                    data: 'spam_type',
                    render: function (data, type, row) {
                        return '<span class="table-main__type"><span class="table-main__type-icon"><svg class="icon"><use xlink:href="/wp-content/plugins/safezone/admin/images/icons.svg#wordpress"></use></svg></span><span class="table-main__type-text">' + data + '</span></span>';
                    }
                },
                {
                    data: 'user_agent',
                    render: function (data, type, row) {
                        if (data !== null) {
                            return '<small>' + data + '</small>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                }
            ]);
        }

        const whiteListTableSelector = '#whitelistTable';
        if ($(whiteListTableSelector).length) {

            const whitelist_table = safezone_table(whiteListTableSelector, 'get_whitelist_table', [
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="row-select form-check-input" value="' + data + '" />';
                    }
                },
                {
                    data: 'ip',
                    render: function (data, type, row) {
                        return '<span class="text-blue-40 shadow-none">' + data + '</span>'
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<span class="table-main__lang"><img class="table-main__lang-flag" src="/wp-content/plugins/safezone/admin/images/flags/' + data.country_code + '.svg" alt="flag"><span class="table-main__lang-name">' + data.country + '</span></span>';
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<div class="d-flex flex-column"><div class="text-gray-40">' + data.hostname + '</div><div class="text-gray-40">' + data.isp + '</div></div>';
                    }
                },
                {
                    data: 'ip_version',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: 'timezone',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: 'location',
                    render: function (data, type, row) {
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: 'null',
                    render: function (data, type, row) {
                        return '<div class="foundation-table__actions"><a href="javascript:void(0);" id="removeWhitelist" class="text-gray-10" data-id="' + row.id + '">Delete</a></div>';
                    }
                }
            ]);

            $('#whitelist_select_all').on('click', function () {
                const rows = whitelist_table.rows({'search': 'applied'}).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);

                selectedIds = [];
                if (this.checked) {
                    whitelist_table.rows({'search': 'applied'}).data().each(function (row) {
                        selectedIds.push(row.id);
                    });
                }
                if (selectedIds.length === 0) {
                    $('.table-actions').hide()
                } else {
                    $('.table-actions').show()
                }
            });

            $('.add_whitelist').on('click', function (e) {
                e.preventDefault();
                $('.splash-screen').addClass('show')
                const $this = $(this);
                $this.prop('disabled', true);
                const t = $(this)
                const payload = {
                    action: 'add_whitelist',
                    security: safezone.token,
                    payload: {
                        ip: $('.whitelist_ip').val()
                    }
                }
                $.post(ajaxurl, payload, function (response) {
                    if (response.success) {
                        toastify('success', response.message);
                        $('.whitelist_ip').val('')
                        whitelist_widget().then(r => {
                        });
                        whitelist_table.row.add(response.data).draw(false).order([0, 'desc']).draw();
                    } else {
                        toastify('error', response.message);
                    }
                    $this.prop('disabled', false);
                    $('.splash-screen').removeClass('show')
                }).fail(function (xhr, status, error) {
                    toastify('error', status + " : " + error);
                });
            });

            $(whiteListTableSelector).on('click', '#removeWhitelist', function (e) {
                e.preventDefault();
                const whiteListId = $(this).data('id');
                $('#removeWhitelistModal').data('id', whiteListId).modal('show');
            });

            $(document).on('click', '.deleteWhiteList', function (e) {
                e.preventDefault();
                const whiteListId = $('#removeWhitelistModal').data('id');
                delete_whitelist(whiteListId).then(r => {
                    $('#removeWhitelistModal').modal('hide');
                    whitelist_table.ajax.reload();
                    whitelist_widget().then(r => {
                    });
                });
            });

        }

        const logsTableSelector = '#logsTable';
        if ($(logsTableSelector).length) {
            const logs_table = safezone_table(logsTableSelector, 'get_logs_table', [
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="row-select form-check-input" value="' + data + '" />';
                    }
                },
                {
                    data: 'username',
                    render: function (data, type, row) {
                        return '<span class="text-blue-40 shadow-none">' + data + '</span>'
                    },
                },
                {
                    data: 'category',
                    render: function (data, type, row) {
                        return '<span class="badge badge--error" title="' + data + '"><span class="badge__dot"></span><span class="badge__text">' + data + '</span></span>';
                    }
                },
                {
                    data: 'activity',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return '<span class="text-gray-100">' + data + '</span>';
                    }
                }
            ]);
            $('#logs_search_input').on('keyup', function () {
                logs_table.search(this.value).draw();
            });

            $('#logs_category_filter').change(function () {
                logs_table.ajax.reload();
            });

            $('#logs_select_all').on('click', function () {
                const rows = logs_table.rows({'search': 'applied'}).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);

                selectedIds = [];
                if (this.checked) {
                    logs_table.rows({'search': 'applied'}).data().each(function (row) {
                        selectedIds.push(row.id);
                    });
                }
                if (selectedIds.length === 0) {
                    $('.export-button').hide()
                } else {
                    $('.export-button').show()
                }
            });

            $(document).on('click', '.export-button', function(e){
                e.preventDefault();
                exportTableToCSV('logs_' + Date.now() + '.csv');
            });
        }

        const firewallTableSelector = '#firewallTable';
        if ($(firewallTableSelector).length) {
            const firewall_table = safezone_table(firewallTableSelector, 'get_firewall_table', [
                {
                    data: 'ip',
                    render: function (data, type, row) {
                        return '<span class="text-blue-40 shadow-none">' + data + '</span>'
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<span class="table-main__lang"><img class="table-main__lang-flag" src="/wp-content/plugins/safezone/admin/images/flags/' + data.country_code + '.svg" alt="flag"><span class="table-main__lang-name">' + data.country + '</span></span>';
                    }
                },
                {
                    data: 'firewall_type',
                    render: function (data, type, row) {
                        return '<span class="table-main__type"><span class="table-main__type-text">' + data + '</span></span>';
                    }
                },
                {
                    data: 'activity',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: 'user_agent',
                    render: function (data, type, row) {
                        return '<small>' + data + '</small>'
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return '<span class="text-gray-100">' + data + '</span>';
                    }
                }
            ]);
            $('#firewall_search_input').on('keyup', function () {
                firewall_table.search(this.value).draw();
            });
        }

        const malwareTableSelector = '#malwareTable';
        if ($(malwareTableSelector).length) {
            const malware_table = safezone_table(malwareTableSelector, 'get_malware_table', [
                {
                    data: 'activity',
                    render: function (data, type, row) {
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    data: 'malware_type',
                    render: function (data, type, row) {
                        return '<span class="badge badge--error"><span class="badge__dot"></span><span class="badge__text">' + data + '</span></span>';
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.step === "4"){
                            return '<div class="foundation-table__actions"></div>';
                        }
                        return '<div class="foundation-table__actions"><a href="javascript:void(0);" data-id="' + data.id + '" class="text-gray-10 malware-ignore">Ignore</a><a href="javascript:void(0);" data-id="' + data.id + '" class="text-blue-50 malware-detail">Details</a></div>';
                    }
                },
            ]);

            $(document).on('click', '.run-scanner', function (e) {
                e.preventDefault();
                const $thisButton = $(this);
                $thisButton.prop('disabled', true);
                $thisButton.addClass('btn-loading');

                const items = $('.foundation-status__item');

                items.each((index) => {
                    items.eq(index).removeClass('status-good-result').removeClass('status-bad-result');
                })

                function executeStep(step) {
                    if (step > items.length) {
                        return;
                    }

                    if (!safezone.pro && (step === 2 || step === 7)) {
                        executeStep(step + 1);
                        return;
                    }

                    if (step === items.length) {
                        $thisButton.prop('disabled', false);
                        $thisButton.removeClass('btn-loading');
                    }

                    items.eq(step - 1).addClass('status-loading');
                    let payload = {action: 'malware_scanner', security: safezone.token, payload: {step: step}};

                    $.post(ajaxurl, payload, function (response) {
                        if (response.success) {
                            items.eq(step - 1).removeClass('loading').addClass('status-loaded').removeClass('status-loading').removeClass('status-loaded');
                            if (response.data.status === 'success') {
                                items.eq(step - 1).addClass('status-good-result');
                            } else if (response.data.status === 'failed') {
                                items.eq(step - 1).addClass('status-bad-result');
                            }

                            malware_table.ajax.reload();
                            malware_table.columns.adjust().draw();

                            executeStep(step + 1);
                        } else {
                            $thisButton.prop('disabled', false);
                            $thisButton.removeClass('btn-loading');
                        }
                    });
                }

                executeStep(1);
            });

            let malwareId = null;
            let editor = null;
            const modal = $('#code-modal');
            const splash_screen = $('.splash-screen');

            $(document).on('click', '.malware-detail', function (e) {
                e.preventDefault();
                malwareId = $(this).data('id');

                editor = ace.edit("code-area");
                // editor.setReadOnly(true);

                splash_screen.addClass('show');

                modal.on('shown.bs.modal', function () {

                });

                modal.on('hidden.bs.modal', function () {
                    editor.destroy();
                });

                const payload = {
                    action: 'view_code',
                    security: safezone.token,
                    payload: {
                        id: malwareId,
                    }
                }

                $.post(ajaxurl, payload, function (response) {
                    if (response.success) {
                        editor.setValue(response.data.file);
                        modal.modal("show");
                        splash_screen.removeClass('show');

                    } else {
                        toastify('error', response.message);
                    }
                });

            });

            $(document).on('click', '.file-update', function (e) {
                e.preventDefault();
                const t = $(this);
                t.addClass('btn-loading');

                const payload = {
                    action: 'update_file',
                    security: safezone.token,
                    payload: {
                        id: malwareId,
                        code: editor.getValue()
                    }
                }

                $.post(ajaxurl, payload, function (response) {
                    if (response.success) {
                        toastify('success', response.message);
                        t.removeClass('btn-loading');
                        modal.modal("hide");
                    } else {
                        toastify('error', response.message);
                    }
                });

            });

            $(document).on('click', '.file-delete', function (e) {
                e.preventDefault();
                const t = $(this);
                t.addClass('btn-loading');

                const payload = {
                    action: 'delete_file',
                    security: safezone.token,
                    payload: {
                        id: malwareId,
                    }
                }

                $.post(ajaxurl, payload, function (response) {
                    if (response.success) {
                        toastify('success', response.message);
                        t.removeClass('btn-loading');
                        malware_table.ajax.reload();
                        malware_table.columns.adjust().draw();
                        modal.modal("hide");
                    } else {
                        toastify('error', response.message);
                    }
                });
            });

            $(document).on('click', '.malware-ignore', function (e) {
                e.preventDefault();
                $('.splash-screen').addClass('show');

                malwareId = $(this).data('id');

                const payload = {
                    action: 'malware_ignore',
                    security: safezone.token,
                    payload: {
                        id: malwareId,
                    }
                }

                $.post(ajaxurl, payload, function (response) {
                    if (response.success) {
                        toastify('success', response.message);
                        malware_table.ajax.reload();
                        malware_table.columns.adjust().draw();
                    } else {
                        toastify('error', response.message);
                    }
                    $('.splash-screen').removeClass('show');
                });

            })

        }


        const eventsTableSelector = '#eventsTable';
        if ($(eventsTableSelector).length) {
            const firewall_table = safezone_table(eventsTableSelector, 'get_events_table', [
                {
                    data: 'ip',
                    render: function (data, type, row) {
                        return '<span class="text-blue-40 shadow-none">' + data + '</span>'
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<span class="table-main__lang"><img class="table-main__lang-flag" src="/wp-content/plugins/safezone/admin/images/flags/' + data.country_code + '.svg" alt="flag"><span class="table-main__lang-name">' + data.country + '</span></span>';
                    }
                },
                {
                    data: 'firewall_type',
                    render: function (data, type, row) {
                        return '<span class="table-main__type"><span class="table-main__type-text">' + data + '</span></span>';
                    }
                },
                {
                    data: 'activity',
                    render: function (data, type, row) {
                        return '<span class="text-gray-40">' + data + '</span>';
                    }
                },
                {
                    data: 'user_agent',
                    render: function (data, type, row) {
                        return '<small>' + data + '</small>'
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return '<span class="text-gray-100">' + data + '</span>';
                    }
                }
            ]);
            $('#firewall_search_input').on('keyup', function () {
                firewall_table.search(this.value).draw();
            });
        }

        $('tbody').on('change', '.row-select', function () {
            const id = $(this).val();
            if (this.checked) {
                selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(function (value) {
                    return value !== id;
                });
            }
            if (selectedIds.length === 0) {
                $('.export-button').hide()
            } else {
                $('.export-button').show()
            }
        });

        $(document).on('click', '.cancelSubscription', function (e) {
            e.preventDefault();
            $('.splash-screen').addClass('show');
            const payload = {
                action: 'cancel_subscription',
                security: safezone.token,
                payload: {
                    license: safezone.licence_key,
                    hostname: safezone.hostname
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    toastify('error', response.message);
                    $('.splash-screen').removeClass('show')
                }
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show')
            });
        });

        $(document).on('click', '.resumeSubscription', function (e) {
            e.preventDefault();
            $('.splash-screen').addClass('show');
            const payload = {
                action: 'resume_subscription',
                security: safezone.token,
                payload: {
                    license: safezone.licence_key,
                    hostname: safezone.hostname
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    toastify('error', response.message);
                    $('.splash-screen').removeClass('show')
                }
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show')
            });
        });


        $(document).on('click', '.add-license', function (e) {
            e.preventDefault();
            $('.splash-screen').addClass('show');
            const payload = {
                action: 'add_license',
                security: safezone.token,
                payload: {
                    license: $('.license-input').val(),
                    hostname: safezone.hostname
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    toastify('error', response.message);
                    $('.splash-screen').removeClass('show')
                }
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show')
            });
        });

        $('input[name="settings-scheduleRadio"]').change(function() {
            $('.splash-screen').addClass('show');
            const payload = {
                action: 'update_malware_scanning_period',
                security: safezone.token,
                payload: {
                    period: $(this).val()
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    toastify('success', response.message);
                } else {
                    toastify('error', response.message);
                }
                $('.splash-screen').removeClass('show')
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show')
            });
        });

        $(document).on('change', '.update_option', function (e) {
            e.preventDefault();
            const t = $(this);
            $('.splash-screen').addClass('show');
            const payload = {
                action: 'update_option',
                security: safezone.token,
                payload: {
                    key: t.data('key'),
                    value: t.is(':checked') ? "1" : "0"
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    if (payload.payload.key === "sz_enable_autoscanning" && payload.payload.value === "1") {
                        $('.malware-period-area').removeClass('malware-period-area-hidden').addClass('malware-period-area-show');
                    } else {
                        $('.malware-period-area').removeClass('malware-period-area-show').addClass('malware-period-area-hidden');
                    }
                    toastify('success', response.message);
                    live_settings_board().then(r => {
                    });
                } else {
                    toastify('error', response.message);
                }
                $('.splash-screen').removeClass('show');
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show');
            });
        })

        $('.protection_change').on('change', function (e) {
            e.preventDefault();
            $('.splash-screen').addClass('show')
            const t = $(this);
            const payload = {
                action: 'update_option',
                security: safezone?.token,
                payload: {
                    value: t.is(':checked') ? "1" : "0",
                    key: t.data('type')
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    safezone[payload.key] = payload.value;
                    t.closest('.form-check-reverse').find('span').html(payload.payload.value === "1" ? "Active" : "Disabled")
                    toastify('success', response.message);
                    live_settings_board().then(r => {
                    });
                    if(response.data.sz_firewall === "1" && response.data.sz_anti_spam === "1"){
                        $('.heading__protection').removeClass('heading__protection--warning').addClass('heading__protection--success').find('span').html('Providing Full Protection');
                    }else{
                        $('.heading__protection').removeClass('heading__protection--success').addClass('heading__protection--warning').find('span').html('Providing Standard Protection');
                    }
                } else {
                    toastify('error', response.message);
                }
                $('.splash-screen').removeClass('show')
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show')
            });
        });

        if (!safezone.is_pro) {
            $('#toplevel_page_safezone-dashboard ul').append('<li><a href="https://wpsafezone.com/pricing" target="_blank" class="safezone-pro-redirect">Safe Zone Pro</a></li>');
        }

        if ($(".home__chart-container").length > 0) {
            $.get(ajaxurl, {
                action: 'dashboard_data',
                security: safezone.token,
            }, function (response) {
                if (response.success) {
                    stats_chart(response.data);
                }
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
            });
        }

        $(document).on('click', '.subscribe', function (e) {
            e.preventDefault();
            const $this = $(this);
            $this.prop('disabled', true);
            $this.addClass('btn-loading');
            $('.paywall-form').find('input').prop('readonly', true);
            const payload = {
                action: 'subscribe',
                security: safezone.token,
                payload: {
                    email: $('#subscribe_email').val(),
                    firstname: $('#subscribe_firstname').val(),
                    lastname: $('#subscribe_lastname').val(),
                    price: $('input[name="ratio-paymentMethod"]:checked').val(),
                    hostname: safezone.hostname
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    toastify('success', response.message);
                    window.location.href = response.data?.redirect_url;
                } else {
                    toastify('error', response.message);
                }
                $this.removeClass('btn-loading');
                $this.prop('disabled', false);
                $('.paywall-form').find('input').prop('readonly', false);
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
            });
        });

        $(document).on('change', '.counter_select', function (e) {
            e.preventDefault();
            $('.splash-screen').addClass('show')
            const $this = $(this);
            const payload = {
                action: 'get_counter',
                security: safezone.token,
                payload: {
                    period: $this.val(),
                    type: $this.data('type')
                }
            }
            $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    $('#' + $this.data('type') + '_count').html(response.data.count);
                }
                $('.splash-screen').removeClass('show')
            }).fail(function (xhr, status, error) {
                toastify('error', status + " : " + error);
                $('.splash-screen').removeClass('show')
            });
        });

    });

    const delete_whitelist = async (id) => {
        $('.splash-screen').addClass('show')
        const payload = {
            action: 'delete_whitelist',
            security: safezone.token,
            payload: {
                id: id,
            }
        }
        await $.post(ajaxurl, payload, function (response) {
            if (response.success) {
                toastify('success', response.message);
            } else {
                toastify('error', response.message);
            }
            $('.splash-screen').removeClass('show')
        }).fail(function (xhr, status, error) {
            toastify('error', status + " : " + error);
            $('.splash-screen').removeClass('show')
        });
    }
    const whitelist_widget = async () => {
        const payload = {
            action: 'whitelist_widget',
            security: safezone.token
        }
        await $.post(ajaxurl, payload, function (response) {
            if (response.success) {
                $('#total_whitelist').html(response.data?.total_items);
                $('#last_date').html(response.data?.last_item_date);

                if (response.data?.last_items.length > 0) {
                    let render = '';
                    response.data?.last_items.map((item) => {
                        render += `<div class="sz-card-order__item"><span class="sz-card-order__item-value shadow-none">${item?.ip}</span><span class="sz-card-order__item-text"><span class="text-gray-40">${item?.created_at}</span></span></div>`;
                    });
                    $('#last_items').html(render);
                }

            }
        });
    }

    const firewall_widget = async () => {
        const payload = {
            action: 'firewall_widget',
            security: safezone.token
        }
        await $.post(ajaxurl, payload, function (response) {
            if (response.success) {
                let render = '';
                response.data?.map((item, key) => {
                    render += `<div class="sz-card-order__item"><span class="sz-card-order__item-value shadow-none">${item.ip}</span><span class="sz-card-order__item-text"><b class="text-gray-30">${key+1}.</b></span></div>`;
                });
            }
        });
    }


    const live_settings_board = async () => {
        const board = $('.live_settings_board');

        const blocked_spam = $('#blocked_spam_count');
        const blocked_ip = $('#blocked_ip_count');

        const bad_bot = $('#bad_bots_count');
        const login_protection = $('#login_protection_count');

        const settings_board = $('.setting_status');
        if (board.length) {
            $('.splash-screen').addClass('show')
            const payload = {
                action: 'live_settings',
                security: safezone.token,
                payload: {
                    type: board.data('type')
                }
            }
            await $.post(ajaxurl, payload, function (response) {
                if (response.success) {
                    blocked_spam.html(response.data?.blocked_spams_count);
                    blocked_ip.html(response.data?.blocked_ips_count);
                    bad_bot.html(response.data?.bad_bots_count);
                    login_protection.html(response.data?.login_protections_count);
                    let settings = response.data?.settings.splice(0, 3);
                    let lines = ''
                    settings.map((item) => {
                        lines += `<div class="foundation-card__switch"><label class="form-check form-switch form-check-reverse"><span class="form-check-label">${item.title}</span><input class="form-check-input update_option" type="${item.type}" data-key="${item.key}" role="switch"/></label></div>`;
                    })
                    board.html(lines);
                    if (settings.length > 2) {
                        settings_board.html(settings_status_text('error', 'Update Required'));
                    } else {
                        settings_board.html(settings_status_text('warning', 'Update Required'))
                    }
                } else {
                    board.html('')
                    settings_board.html(settings_status_text('success', 'Protected'));
                }
                $('.splash-screen').removeClass('show')
            });
        }
    }
    const settings_status_text = (type, message) => {
        return `<span class="foundation-card__badge foundation-card__badge--${type}"><svg class="icon"><use xlink:href="/wp-content/plugins/safezone/admin/images/icons.svg#info-outline"></use></svg>${message}</span>`;
    }
    const toastify = (status, message) => {
        Toastify({
            text: message,
            duration: 5000,
            newWindow: true,
            gravity: "bottom", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: status === "success" ? "#389547" : "red",
            },
            offset: {
                x: 0,
                y: 30,
            },
            onClick: function () {
            } // Callback after click
        }).showToast();
    }
    const safezone_table = (selector, route, columns) => {
        return $(selector).DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            pageLength: 20,
            info: false,
            lengthChange: false,
            ajax: {
                url: ajaxurl,
                type: 'POST',
                data: function (d) {
                    d.action = route;
                    d.page = (d.start / d.length) + 1;
                    d.per_page = d.length;
                    d.search = d.search.value;
                    d.category_filter = $('#logs_category_filter').val() || '';
                }
            },
            columns: columns,
            dom: 'lrtip',
            stateSave: false,
            deferRender: true,
            language: {
                emptyTable: 'There is no information here yet.',
                zeroRecords: 'There is no information here yet.',
                paginate: {
                    previous: "<span class='page-link'>&laquo;</span>",
                    next: "<span class='page-link'>&raquo;</span>"
                }
            }
        });
    }

    const stats_chart = (data) => {
        if (0 < $(".home__chart-container").length) {
            const t = echarts.init(document.querySelector(".home__chart-container"));
            const e = {
                title: {
                    text: "Stats",
                    padding: [14, 24],
                    textStyle: {fontSize: 16, fontWeight: 480, lineHeight: 22}
                },
                tooltip: {
                    trigger: "axis",
                    formatter: function (e) {
                        const t = e.map(
                            (e) => `
                                <div style="
                                    display:flex;
                                    align-items: center;
                                    gap: 8px;
                                ">
                                    <div style="height: 8px; width: 8px; border-radius: 50%; background-color: ${e.color}"></div>
                                    <div style="
                                      color: #8C8F94;
                                      font-size: 14px;
                                      font-weight: 440;
                                      line-height: 20px;
                                      letter-spacing: -0.32px;
                                    ">${e.seriesName}</div>
                                    <div style="
                                      color: #101517;
                                      font-size: 14px;
                                      font-weight: 600;
                                      line-height: 20px;
                                      margin-left: auto;
                                    ">${Math.round(e.value)}</div>
                                </div>
                            `
                        );
                        return `
                                <div style="
                                  border-radius: 10px;
                                  border: 1px solid #F0F0F1;
                                  background: #FFF;
                                  box-shadow: 0 1px 2px 0 rgba(16, 24, 40, 0.05);
                                  display: inline-flex;
                                  padding: 19px;
                                  flex-direction: column;
                                  gap: 16px;
                                  margin: -30px;
                                  min-width: 236px;
                                ">
                                  <div style="
                                    color: #101517;
                                    font-size: 14px;
                                    font-weight: 600;
                                    line-height: 20px;
                                    letter-spacing: -0.32px;
                                  ">
                                      ${e[0].axisValue}
                                  </div>
                                  <div style="
                                    display:flex;
                                    flex-direction: column;
                                    gap: 10px;
                                  ">
                                      ${t.join("")}
                                  </div>
                                </div>
                            `;
                    },
                },
                legend: {
                    data: ["Malicious Activities", "Spam Activities", "Firewall Detections"],
                    icon: "circle",
                    right: 24,
                    top: 14,
                    textStyle: {color: "#8C8F94", fontSize: 14, fontWeight: 400},
                    itemWidth: 8,
                    itemHeight: 8,
                    itemGap: 24,
                },
                grid: {top: "86px", left: "24px", right: "24px", bottom: "24px", containLabel: !0},
                xAxis: [
                    {
                        type: "category",
                        boundaryGap: !1,
                        data: data?.days,
                        axisLabel: {align: "center", margin: 14, textStyle: {color: "#8C8F94", fontSize: 12}},
                        axisTick: {length: 0},
                        axisLine: {show: !1},
                    },
                ],
                yAxis: [{
                    type: "value",
                    axisLabel: {textStyle: {color: "#8C8F94", fontSize: 12}},
                    splitLine: {lineStyle: {color: "#F2F4F7"}},
                    minInterval: 1,
                }],
                series: [
                    {
                        name: "Firewall Detections",
                        type: "line",
                        emphasis: {focus: "series"},
                        symbolSize: 0,
                        symbol: "circle",
                        itemStyle: {color: "#4F94D4"},
                        data: data?.firewall.map((e, t, i) => ({
                            value: e,
                            symbolSize: e === Math.max(...i) ? 12 : void 0
                        })),
                        lineStyle: {color: "rgb(79,148,212)"},
                        areaStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                {offset: 0, color: "rgba(79,148,212,0.10)"},
                                {offset: 1, color: "rgba(255, 255, 255, 0.00)"},
                            ]),
                        },
                    },
                    {
                        name: "Spam Activities",
                        type: "line",
                        emphasis: {focus: "series"},
                        symbolSize: 0,
                        symbol: "circle",
                        itemStyle: {color: "#F6A306"},
                        data: data?.spam.map((e, t, i) => ({
                            value: e,
                            symbolSize: e === Math.max(...i) ? 12 : void 0
                        })),
                        lineStyle: {color: "#F6A306"},
                        areaStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                {offset: 0, color: "rgba(246, 163, 6, 0.10)"},
                                {offset: 1, color: "rgba(255, 255, 255, 0.00)"},
                            ]),
                        },
                    },
                    {
                        name: "Malicious Activities",
                        type: "line",
                        emphasis: {focus: "series"},
                        symbolSize: 0,
                        symbol: "circle",
                        itemStyle: {color: "#FB5607"},
                        data: data?.malware.map((e, t, i) => ({
                            value: e,
                            symbolSize: e === Math.max(...i) ? 12 : void 0
                        })),
                        lineStyle: {color: "#FB5607"},
                        areaStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                {offset: 0, color: "rgba(251, 86, 7, 0.10)"},
                                {offset: 1, color: "rgba(255, 255, 255, 0.00)"},
                            ]),
                        },
                    },
                ],
            };
            t.setOption(e);
        }
    }

    const exportTableToCSV = (filename) => {
        let csv = [];
        let rows = document.querySelectorAll("table tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        let downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";

        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

})(jQuery);
