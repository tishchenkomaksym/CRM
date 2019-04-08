//on change
idForm.find('select').on('change', function(){
    var this_select = $(this),
        this_select_name = this_select.attr('name'),
        select_opt = this_select.val(),
        option_software_complete = '',
        option_software_other_complete = '',
        option_server_software_complete = '',
        option_server_software_other_complete = '',
        option_department = '<option value="1">Выберите отдел</option>\n',
        option_subdepartment = '<option value="1">Выберите подотдел</option>\n',
        option_team = '<option value="1">Выберите команду</option>\n',
        option_subteam = '<option value="1">Выберите подкоманду</option>\n',
        option_room_complete = '<option value="1">Выберите номер кабинета</option>\n',
        option_os = '<option value="1">Выберите операционную систему</option>\n',
        option_position = '<option value="1">Выберите позицию</option>\n',
        department = '',
        sub_department = '',
        team = '',
        sub_team ='',
        position = '';

    if(this_select_name == 'office'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(select_opt == this.country + '_' + this.city + '_' + this.street || select_opt == this.office_name){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                department = this.department_name;
                                option_department += '<option value="' + department + '">' + department + '</option>';
                            });
                        }
                        if(this.rooms !== undefined && this.rooms.length){
                            $.each(this.rooms, function(i, val){
                                if(val !== 'Remote'){
                                    option_room_complete += '<option value="' + [val] + '">Кабинет №' + [val] + '</option>\n';
                                }
                            });
                        }
                    }
                });
            }

            fieldDepartment.html(option_department);
            fieldRoom.html(option_room_complete);

            $('.workstation-list, .software-list, .software-list-other').slideUp();
            $('.workstation-list, .software-list-other').find('input[type="checkbox"]').removeAttr("checked");

            if(select_opt === 'Remote'){
                //fieldOs.val('').closest('.form-group').hide();
                fieldRoom.val('').closest('.form-group').hide();
                fieldWorkstation.val('').closest('.form-group').hide();
                fieldSoftware.val('').closest('.form-group').hide();
                fieldSkype.data('required', 'required');
                fieldSkype.closest('.form-group').show();
            } else {
                //fieldOs.val('1').closest('.form-group').show();
                fieldRoom.val('1').closest('.form-group').show();
                fieldWorkstation.val('1').closest('.form-group').show();
                fieldSoftware.val('1').closest('.form-group').show();
                fieldSoftware.trigger('change');
                fieldTelephone.data('required', 'required');
                fieldTelephone.closest('.form-group').show();

                //fieldTelephone.removeData('required');
                //fieldTelephone.closest('.form-group').hide();
                fieldSkype.removeData('required');
                fieldSkype.closest('.form-group').hide();
            }


            fieldDepartment.val('1');
            fieldDepartment.trigger('change');
            //fieldOs.trigger('change');
        } else {
            $('.software-list, .software-list-other').slideUp();
            fieldSoftware.val('1');
            option_software_complete = '';
            option_software_other_complete = '';
            software_complete.html(option_software_complete);
            software_other_complete.html(option_software_other_complete);
            fieldDepartment.html(option_department);
            fieldDepartment.trigger('change');
        }
    }

    if(this_select_name == 'department'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(fieldOffice.val() == this.country + '_' + this.city + '_' + this.street || fieldOffice.val() == 'Remote'){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                department = this.department_name;
                                if(fieldDepartment.val() == department){
                                    if(this.sub_departments !== undefined && this.sub_departments.length){
                                        $.each(this.sub_departments, function(){
                                            sub_department = this.sub_department_name;
                                            option_subdepartment += '<option value="' + sub_department + '">' + sub_department + '</option>';
                                        });
                                        $('.subdepartment').slideDown();
                                        $('.team, .subteam, .os').slideUp();

                                        fieldSubDepartment.html(option_subdepartment);
                                        fieldSubDepartment.val('1');
                                        fieldSubDepartment.trigger('change');
                                    } else {
                                        $('.subdepartment, .team, .subteam, .os').slideUp();
                                    }

                                    if(this.positions !== undefined && this.positions.length){

                                        $.each(this.positions, function(){
                                            position = this.position_name;
                                            option_position += '<option value="' + position + '">' + position + '</option>';
                                        });

                                        fieldPosition.html(option_position);
                                        fieldPosition.val('1');
                                        fieldPosition.trigger('change');
                                        fieldSubDepartment.val('');
                                        fieldTeam.val('');
                                        fieldSubTeam.val('');
                                    }
                                    /*
                                    if(this.software !== undefined && this.software.length){
                                        $.each(this.software, function(){
                                            os = this.os_name;
                                            option_os += '<option value="' + os + '">' + os + '</option>';
                                        });
                                        $('.os').slideDown();
                                    } else {
                                        $('.os').slideUp();
                                    }
                                    fieldSoftware.val('1');
                                    $('.software-list, .software-list-other').slideUp();
                                    */
                                }
                            });
                        }
                    }
                });
            }
            //fieldSubDepartment.html(option_subdepartment);
            //fieldSubDepartment.val('1');
            //fieldSubDepartment.trigger('change');
            //fieldOs.html(option_os);
            //fieldOs.trigger('change');
        } else {
            fieldSubDepartment.html(option_subdepartment);
            fieldSubDepartment.val('1');
            fieldSubDepartment.trigger('change');
            $('.software-list, .software-list-other, .subdepartment, .team, .subteam, .os').slideUp();
            //fieldSoftware.val('1');
            //option_software_complete = '';
            //software_complete.html(option_software_complete);
            fieldPosition.html(option_position);
            fieldPosition.val('1');
            fieldPosition.trigger('change');
        }
        $('.po-list').slideUp();
    }

    if(this_select_name == 'subdepartment'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(fieldOffice.val() == this.country + '_' + this.city + '_' + this.street || fieldOffice.val() == 'Remote'){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                if(fieldDepartment.val() == this.department_name){
                                    if(this.sub_departments !== undefined && this.sub_departments.length){
                                        $.each(this.sub_departments, function(){
                                            if(fieldSubDepartment.val() == this.sub_department_name){

                                                if(this.teams !== undefined && this.teams.length){
                                                    $.each(this.teams, function(){
                                                        team = this.team_name;
                                                        option_team += '<option value="' + team + '">' + team + '</option>';
                                                    });

                                                    $('.team').slideDown();

                                                    fieldTeam.html(option_team);
                                                    fieldTeam.val('1');
                                                    fieldTeam.trigger('change');
                                                    //$('.subteam, .os').slideUp();
                                                } else {
                                                    $('.team, .subteam, .os').slideUp();
                                                }

                                                if(this.positions !== undefined && this.positions.length){
                                                    $.each(this.positions, function(){
                                                        position = this.position_name;
                                                        option_position += '<option value="' + position + '">' + position + '</option>';
                                                    });

                                                    fieldPosition.html(option_position);
                                                    fieldPosition.val('1');
                                                    fieldPosition.trigger('change');
                                                    fieldTeam.val('');
                                                    fieldSubTeam.val('');
                                                }
                                                /*
                                                if(this.software !== undefined && this.software.length){
                                                    $.each(this.software, function(){
                                                        os = this.os_name;
                                                        option_os += '<option value="' + os + '">' + os + '</option>';
                                                    });
                                                    $('.os').slideDown();
                                                } else {
                                                    $('.os').slideUp();
                                                }
                                                fieldSoftware.val('1');
                                                $('.software-list, .software-list-other').slideUp();
                                                */
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
        } else {
            fieldTeam.html(option_team);
            fieldTeam.val('1');
            fieldTeam.trigger('change');
            $('.software-list, .software-list-other, .team, .subteam, .os').slideUp();
            fieldPosition.html(option_position);
            fieldPosition.val('1');
            fieldPosition.trigger('change');
        }
    }

    if(this_select_name == 'team'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(fieldOffice.val() == this.country + '_' + this.city + '_' + this.street || fieldOffice.val() == 'Remote'){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                if(fieldDepartment.val() == this.department_name){
                                    if(this.sub_departments !== undefined && this.sub_departments.length){
                                        $.each(this.sub_departments, function(){
                                            if(fieldSubDepartment.val() == this.sub_department_name){
                                                if(this.teams !== undefined && this.teams.length){
                                                    $.each(this.teams, function(){
                                                        if(fieldTeam.val() == this.team_name){

                                                            if(this.sub_teams !== undefined && this.sub_teams.length){
                                                                $.each(this.sub_teams, function(){
                                                                    subteam = this.sub_team_name;
                                                                    option_subteam += '<option value="' + subteam + '">' + subteam + '</option>';
                                                                });

                                                                $('.subteam').slideDown();

                                                                fieldSubTeam.html(option_subteam);
                                                                fieldSubTeam.val('1');
                                                                fieldSubTeam.trigger('change');
                                                            } else {
                                                                $('.subteam').slideUp();
                                                            }

                                                            if(this.positions !== undefined && this.positions.length){
                                                                $.each(this.positions, function(){
                                                                    position = this.position_name;
                                                                    option_position += '<option value="' + position + '">' + position + '</option>';
                                                                });
                                                                fieldPosition.html(option_position);
                                                                fieldPosition.val('1');
                                                                fieldPosition.trigger('change');
                                                                fieldSubTeam.val('');
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
        } else {
            fieldSubTeam.html(option_subteam);
            fieldSubTeam.val('1');
            fieldSubTeam.trigger('change');
            $('.software-list, .software-list-other, .subteam, .os').slideUp();
            fieldPosition.html(option_position);
            fieldPosition.val('1');
            fieldPosition.trigger('change');
        }
    }

    if(this_select_name == 'subteam'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(fieldOffice.val() == this.country + '_' + this.city + '_' + this.street || fieldOffice.val() == 'Remote'){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                if(fieldDepartment.val() == this.department_name){
                                    if(this.sub_departments !== undefined && this.sub_departments.length){
                                        $.each(this.sub_departments, function(){
                                            if(fieldSubDepartment.val() == this.sub_department_name){
                                                if(this.teams !== undefined && this.teams.length){
                                                    $.each(this.teams, function(){
                                                        if(fieldTeam.val() == this.team_name){
                                                            if(this.sub_teams !== undefined && this.sub_teams.length){
                                                                $.each(this.sub_teams, function(){
                                                                    if(fieldSubTeam.val() == this.sub_team_name){

                                                                        if(this.positions !== undefined && this.positions.length){
                                                                            $.each(this.positions, function(){
                                                                                position = this.position_name;
                                                                                option_position += '<option value="' + position + '">' + position + '</option>';
                                                                            });
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
            fieldPosition.html(option_position);
            fieldPosition.val('1');
            fieldPosition.trigger('change');
        } else {
            fieldPosition.html(option_position);
            fieldPosition.val('1');
            fieldPosition.trigger('change');
        }
    }

    if(this_select_name == 'position'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(fieldOffice.val() == this.country + '_' + this.city + '_' + this.street || fieldOffice.val() == 'Remote'){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                if(fieldDepartment.val() == this.department_name){
                                    if(this.sub_departments !== undefined && this.sub_departments.length){
                                        $.each(this.sub_departments, function(){
                                            if(fieldSubDepartment.val() == this.sub_department_name){
                                                if(this.teams !== undefined && this.teams.length){
                                                    $.each(this.teams, function(){
                                                        if(fieldTeam.val() == this.team_name){
                                                            if(this.sub_teams !== undefined && this.sub_teams.length){
                                                                $.each(this.sub_teams, function(){
                                                                    if(fieldSubTeam.val() == this.sub_team_name){
                                                                        if(this.positions !== undefined && this.positions.length){
                                                                            $.each(this.positions, function(){
                                                                                if(fieldPosition.val() == this.position_name){
                                                                                    if(this.software !== undefined && this.software.length){
                                                                                        $.each(this.software, function(){
                                                                                            os = this.os_name;
                                                                                            option_os += '<option value="' + os + '">' + os + '</option>';
                                                                                        });
                                                                                        $('.os').slideDown();
                                                                                    } else {
                                                                                        $('.os').slideUp();
                                                                                    };
                                                                                }
                                                                            });
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                            if(this.positions !== undefined && this.positions.length){
                                                                $.each(this.positions, function(){
                                                                    if(fieldPosition.val() == this.position_name){
                                                                        if(this.software !== undefined && this.software.length){
                                                                            $.each(this.software, function(){
                                                                                os = this.os_name;
                                                                                option_os += '<option value="' + os + '">' + os + '</option>';
                                                                            });
                                                                            $('.os').slideDown();
                                                                        } else {
                                                                            $('.os').slideUp();
                                                                        };
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                                if(this.positions !== undefined && this.positions.length){
                                                    $.each(this.positions, function(){
                                                        if(fieldPosition.val() == this.position_name){
                                                            if(this.software !== undefined && this.software.length){
                                                                $.each(this.software, function(){
                                                                    os = this.os_name;
                                                                    option_os += '<option value="' + os + '">' + os + '</option>';
                                                                });
                                                                $('.os').slideDown();
                                                            } else {
                                                                $('.os').slideUp();
                                                            };
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                    if(this.positions !== undefined && this.positions.length){
                                        $.each(this.positions, function(){
                                            if(fieldPosition.val() == this.position_name){
                                                if(this.software !== undefined && this.software.length){
                                                    $.each(this.software, function(){
                                                        os = this.os_name;
                                                        option_os += '<option value="' + os + '">' + os + '</option>';
                                                    });
                                                    $('.os').slideDown();
                                                } else {
                                                    $('.os').slideUp();
                                                };
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
            fieldOs.html(option_os);
            fieldOs.trigger('change');
        } else {
            $('.software-list, .software-list-other, .os').slideUp();
            fieldSoftware.val('1');
            option_software_complete = '';
            software_complete.html(option_software_complete);
        }
    }
    if(this_select_name == 'os'){
        if(select_opt != '1'){
            if(offices.length){
                $.each(offices, function(){
                    if(fieldOffice.val() == this.country + '_' + this.city + '_' + this.street || fieldOffice.val() == 'Remote'){
                        if(this.departments !== undefined && this.departments.length){
                            $.each(this.departments, function(){
                                if(fieldDepartment.val() == this.department_name){
                                    if(this.sub_departments !== undefined && this.sub_departments.length){
                                        $.each(this.sub_departments, function(){
                                            if(fieldSubDepartment.val() == this.sub_department_name){
                                                if(this.teams !== undefined && this.teams.length){
                                                    $.each(this.teams, function(){
                                                        if(fieldTeam.val() == this.team_name){
                                                            if(this.sub_teams !== undefined && this.sub_teams.length){
                                                                $.each(this.sub_teams, function(){
                                                                    if(fieldSubTeam.val() == this.sub_team_name){
                                                                        if(this.positions !== undefined && this.positions.length){
                                                                            $.each(this.positions, function(){
                                                                                if(fieldPosition.val() == this.position_name){
                                                                                    if(this.software !== undefined && this.software.length){
                                                                                        $.each(this.software, function(){
                                                                                            if(fieldOs.val() == this.os_name){
                                                                                                if(this.software_list !== undefined){
                                                                                                    option_software_complete += '<ul class="list-1">';
                                                                                                    $.each(this.software_list, function(i, val){
                                                                                                        option_software_complete += '<li>' + val + '<input name="' + i + '" type="hidden" value="' + i + '"></li>'
                                                                                                    });
                                                                                                    option_software_complete += '</ul>';
                                                                                                }
                                                                                            }
                                                                                        });
                                                                                    }
                                                                                }
                                                                            });
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                            if(this.positions !== undefined && this.positions.length){
                                                                $.each(this.positions, function(){
                                                                    if(fieldPosition.val() == this.position_name){
                                                                        if(this.software !== undefined && this.software.length){
                                                                            $.each(this.software, function(){
                                                                                if(fieldOs.val() == this.os_name){
                                                                                    if(this.software_list !== undefined){
                                                                                        option_software_complete += '<ul class="list-1">';
                                                                                        $.each(this.software_list, function(i, val){
                                                                                            option_software_complete += '<li>' + val + '<input name="' + i + '" type="hidden" value="' + i + '"></li>'
                                                                                        });
                                                                                        option_software_complete += '</ul>';
                                                                                    }
                                                                                }
                                                                            });
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                                if(this.positions !== undefined && this.positions.length){
                                                    $.each(this.positions, function(){
                                                        if(fieldPosition.val() == this.position_name){
                                                            if(this.software !== undefined && this.software.length){
                                                                $.each(this.software, function(){
                                                                    if(fieldOs.val() == this.os_name){
                                                                        if(this.software_list !== undefined){
                                                                            option_software_complete += '<ul class="list-1">';
                                                                            $.each(this.software_list, function(i, val){
                                                                                option_software_complete += '<li>' + val + '<input name="' + i + '" type="hidden" value="' + i + '"></li>'
                                                                            });
                                                                            option_software_complete += '</ul>';
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                    if(this.positions !== undefined && this.positions.length){
                                        $.each(this.positions, function(){
                                            if(fieldPosition.val() == this.position_name){
                                                if(this.software !== undefined && this.software.length){
                                                    $.each(this.software, function(){
                                                        if(fieldOs.val() == this.os_name){
                                                            if(this.software_list !== undefined){
                                                                option_software_complete += '<ul class="list-1">';
                                                                $.each(this.software_list, function(i, val){
                                                                    option_software_complete += '<li>' + val + '<input name="' + i + '" type="hidden" value="' + i + '"></li>'
                                                                });
                                                                option_software_complete += '</ul>';
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                        if(this.positions !== undefined && this.positions.length){
                            $.each(this.positions, function(){
                                if(fieldPosition.val() == this.position_name){
                                    if(this.software !== undefined && this.software.length){
                                        $.each(this.software, function(){
                                            if(fieldOs.val() == this.os_name){
                                                if(this.software_list !== undefined){
                                                    option_software_complete += '<ul class="list-1">';
                                                    $.each(this.software_list, function(i, val){
                                                        option_software_complete += '<li>' + val + '<input name="' + i + '" type="hidden" value="' + i + '"></li>'
                                                    });
                                                    option_software_complete += '</ul>';
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
            software_complete.html(option_software_complete);
        } else {
            $('.software-list, .software-list-other').slideUp();
            fieldSoftware.val('1');
            option_software_complete = '';
            software_complete.html(option_software_complete);
        }
    }

